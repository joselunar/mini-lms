<?php

namespace App\Controllers\Api;

use App\Services\EnrollmentService;
use App\Services\ProgressService;
use CodeIgniter\HTTP\ResponseInterface;

class Lessons extends BaseApiController
{
    public function complete(int $id): ResponseInterface
    {
        return $this->handle(function () use ($id) {
            $result = $this->progress()->completeLesson(service('currentUser')->id(), $id);

            return $this->ok([
                'message'           => $result['already_completed']
                    ? 'Aula já estava concluída.'
                    : 'Aula marcada como concluída.',
                'already_completed' => $result['already_completed'],
                'enrollment'        => $result['enrollment'],
            ]);
        });
    }

    private function progress(): ProgressService
    {
        $enrollments = new EnrollmentService(
            model(\App\Models\CourseModel::class),
            model(\App\Models\LessonModel::class),
            model(\App\Models\EnrollmentModel::class),
        );

        return new ProgressService(
            model(\App\Models\CourseModel::class),
            model(\App\Models\LessonModel::class),
            model(\App\Models\EnrollmentModel::class),
            model(\App\Models\LessonProgressModel::class),
            $enrollments,
        );
    }
}
