<?php

namespace App\Services;

use App\Domain\CourseStatus;
use App\Domain\DomainException;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;

class EnrollmentService
{
    public function __construct(
        private readonly CourseModel $courses,
        private readonly LessonModel $lessons,
        private readonly EnrollmentModel $enrollments,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function enroll(int $userId, int $courseId): array
    {
        $course = $this->courses->find($courseId);

        if ($course === null) {
            throw new DomainException('Curso não encontrado.', 404);
        }

        $status = new CourseStatus($course['status']);

        if (! $status->canEnroll()) {
            throw new DomainException('Só é possível matricular-se em cursos publicados.', 409);
        }

        if ($this->lessons->countByCourse($courseId) === 0) {
            throw new DomainException('Este curso ainda não possui aulas.', 422);
        }

        $existing = $this->enrollments->findByUserAndCourse($userId, $courseId);

        if ($existing !== null) {
            throw new DomainException('Aluno já matriculado neste curso.', 409);
        }

        $this->enrollments->insert([
            'user_id'          => $userId,
            'course_id'        => $courseId,
            'status'           => 'em_andamento',
            'progress_percent' => 0,
            'enrolled_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->enrollments->find($this->enrollments->getInsertID());
    }

    /**
     * @return array<string, mixed>
     */
    public function requireEnrollment(int $userId, int $courseId): array
    {
        $enrollment = $this->enrollments->findByUserAndCourse($userId, $courseId);

        if ($enrollment === null) {
            throw new DomainException('É necessário estar matriculado para esta ação.', 403);
        }

        return $enrollment;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listForUser(int $userId): array
    {
        return $this->enrollments->byUser($userId);
    }
}
