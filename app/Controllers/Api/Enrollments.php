<?php

namespace App\Controllers\Api;

use App\Services\EnrollmentService;
use CodeIgniter\HTTP\ResponseInterface;

class Enrollments extends BaseApiController
{
    public function index(): ResponseInterface
    {
        $userId = service('currentUser')->id();

        return $this->ok($this->service()->listForUser($userId));
    }

    public function create(): ResponseInterface
    {
        return $this->handle(function () {
            $courseId = (int) ($this->request->getJsonVar('course_id') ?? 0);

            if ($courseId <= 0) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 422,
                    'error'   => 'validation',
                    'message' => 'Informe course_id.',
                ]);
            }

            $enrollment = $this->service()->enroll(service('currentUser')->id(), $courseId);

            return $this->created($enrollment, 'Matrícula realizada.');
        });
    }

    private function service(): EnrollmentService
    {
        return new EnrollmentService(
            model(\App\Models\CourseModel::class),
            model(\App\Models\LessonModel::class),
            model(\App\Models\EnrollmentModel::class),
        );
    }
}
