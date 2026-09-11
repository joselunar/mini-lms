<?php

namespace App\Services;

use App\Domain\CourseStatus;
use App\Domain\DomainException;
use App\Domain\ProgressCalculator;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use App\Models\LessonProgressModel;

class ProgressService
{
    public function __construct(
        private readonly CourseModel $courses,
        private readonly LessonModel $lessons,
        private readonly EnrollmentModel $enrollments,
        private readonly LessonProgressModel $progress,
        private readonly EnrollmentService $enrollmentService,
        private readonly ProgressCalculator $calculator = new ProgressCalculator(),
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function completeLesson(int $userId, int $lessonId): array
    {
        $lesson = $this->lessons->find($lessonId);

        if ($lesson === null) {
            throw new DomainException('Aula não encontrada.', 404);
        }

        $course = $this->courses->find((int) $lesson['course_id']);

        if ($course === null) {
            throw new DomainException('Curso da aula não encontrado.', 404);
        }

        $status = new CourseStatus($course['status']);

        if (! $status->canReceiveProgress()) {
            throw new DomainException('Não é possível concluir aulas de um curso em rascunho.', 409);
        }

        $enrollment = $this->enrollmentService->requireEnrollment($userId, (int) $course['id']);
        $alreadyDone = $this->progress->alreadyCompleted((int) $enrollment['id'], $lessonId);

        if (! $alreadyDone) {
            $this->progress->insert([
                'enrollment_id' => $enrollment['id'],
                'lesson_id'     => $lessonId,
                'completed_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $updated = $this->recalculate((int) $enrollment['id'], (int) $course['id']);

        return [
            'already_completed' => $alreadyDone,
            'lesson'            => $lesson,
            'enrollment'        => $updated,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function recalculate(int $enrollmentId, int $courseId): array
    {
        $total     = $this->lessons->countByCourse($courseId);
        $completed = $this->progress->countCompleted($enrollmentId);
        $percent   = $this->calculator->percent($completed, $total);
        $finished  = $this->calculator->isCourseComplete($completed, $total);

        $this->enrollments->update($enrollmentId, [
            'progress_percent' => $percent,
            'status'           => $finished ? 'concluido' : 'em_andamento',
            'completed_at'     => $finished ? date('Y-m-d H:i:s') : null,
        ]);

        return $this->enrollments->find($enrollmentId);
    }
}
