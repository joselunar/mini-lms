<?php

namespace App\Services;

use App\Domain\CourseStatus;
use App\Domain\DomainException;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\LessonProgressModel;

class CourseService
{
    public function __construct(
        private readonly CourseModel $courses,
        private readonly LessonModel $lessons,
        private readonly LessonProgressModel $progress,
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function catalog(): array
    {
        $items = $this->courses->published();

        return array_map(fn (array $course): array => $this->withLessonCount($course), $items);
    }

    /**
     * @return array<string, mixed>
     */
    public function publicCourse(int $id): array
    {
        $course = $this->requireCourse($id);
        $status = new CourseStatus($course['status']);

        if (! $status->isVisibleInCatalog()) {
            throw new DomainException('Curso indisponível no catálogo.', 404);
        }

        $course['lessons'] = $this->lessons->byCourse($id);

        return $course;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $payload = $this->payload($data, CourseStatus::DRAFT);

        if (! $this->courses->insert($payload)) {
            throw new DomainException(implode(' ', $this->courses->errors()), 422);
        }

        return $this->courses->find($this->courses->getInsertID());
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function addLesson(int $courseId, array $data): array
    {
        $this->requireCourse($courseId);

        $payload = [
            'course_id'        => $courseId,
            'title'            => trim((string) ($data['title'] ?? '')),
            'content'          => trim((string) ($data['content'] ?? '')) ?: null,
            'position'         => $this->lessons->nextPosition($courseId),
            'duration_minutes' => (int) ($data['duration_minutes'] ?? 10),
        ];

        if (! $this->lessons->insert($payload)) {
            throw new DomainException(implode(' ', $this->lessons->errors()), 422);
        }

        return $this->lessons->find($this->lessons->getInsertID());
    }

    /**
     * @return array<string, mixed>
     */
    public function publish(int $courseId): array
    {
        $course = $this->requireCourse($courseId);
        $status = new CourseStatus($course['status']);

        if (! $status->canPublish()) {
            throw new DomainException('Somente cursos em rascunho podem ser publicados.', 409);
        }

        if ($this->lessons->countByCourse($courseId) === 0) {
            throw new DomainException('Publique apenas cursos com pelo menos uma aula.', 422);
        }

        $this->courses->update($courseId, ['status' => CourseStatus::PUBLISHED]);

        return $this->courses->find($courseId);
    }

    /**
     * @return array<string, mixed>
     */
    public function close(int $courseId): array
    {
        $course = $this->requireCourse($courseId);
        $status = new CourseStatus($course['status']);

        if (! $status->canClose()) {
            throw new DomainException('Somente cursos publicados podem ser encerrados.', 409);
        }

        $this->courses->update($courseId, ['status' => CourseStatus::CLOSED]);

        return $this->courses->find($courseId);
    }

    /**
     * @return array<string, mixed>
     */
    public function courseForStudent(int $courseId, int $userId): array
    {
        $course = $this->publicCourse($courseId);
        $enrollment = model(\App\Models\EnrollmentModel::class)->findByUserAndCourse($userId, $courseId);
        $completed = [];

        if ($enrollment !== null) {
            $completed = $this->progress->completedLessonIds((int) $enrollment['id']);
        }

        foreach ($course['lessons'] as &$lesson) {
            $lesson['completed'] = in_array((int) $lesson['id'], $completed, true);
        }

        $course['enrollment'] = $enrollment;

        return $course;
    }

    /**
     * @return array<string, mixed>
     */
    private function requireCourse(int $id): array
    {
        $course = $this->courses->find($id);

        if ($course === null) {
            throw new DomainException('Curso não encontrado.', 404);
        }

        return $course;
    }

    /**
     * @param array<string, mixed> $course
     * @return array<string, mixed>
     */
    private function withLessonCount(array $course): array
    {
        $course['lessons_count'] = $this->lessons->countByCourse((int) $course['id']);

        return $course;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function payload(array $data, string $status): array
    {
        $title = trim((string) ($data['title'] ?? ''));

        return [
            'title'          => $title,
            'slug'           => $this->slug((string) ($data['slug'] ?? $title)),
            'description'    => trim((string) ($data['description'] ?? '')) ?: null,
            'workload_hours' => (int) ($data['workload_hours'] ?? 0),
            'status'         => $status,
        ];
    }

    private function slug(string $value): string
    {
        $slug = mb_strtolower($value);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? $value;
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'curso-' . time();
    }
}
