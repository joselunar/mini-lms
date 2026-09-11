<?php

namespace App\Controllers\Api;

use App\Services\CourseService;
use CodeIgniter\HTTP\ResponseInterface;

class Courses extends BaseApiController
{
    public function index(): ResponseInterface
    {
        return $this->ok($this->courses()->catalog());
    }

    public function show(int $id): ResponseInterface
    {
        return $this->handle(fn () => $this->ok($this->courses()->publicCourse($id)));
    }

    public function create(): ResponseInterface
    {
        return $this->handle(function () {
            $course = $this->courses()->create($this->request->getJSON(true) ?? []);

            return $this->created($course, 'Curso criado em rascunho.');
        });
    }

    public function publish(int $id): ResponseInterface
    {
        return $this->handle(fn () => $this->ok($this->courses()->publish($id)));
    }

    public function close(int $id): ResponseInterface
    {
        return $this->handle(fn () => $this->ok($this->courses()->close($id)));
    }

    public function addLesson(int $id): ResponseInterface
    {
        return $this->handle(function () use ($id) {
            $lesson = $this->courses()->addLesson($id, $this->request->getJSON(true) ?? []);

            return $this->created($lesson, 'Aula adicionada ao curso.');
        });
    }

    private function courses(): CourseService
    {
        return new CourseService(
            model(\App\Models\CourseModel::class),
            model(\App\Models\LessonModel::class),
            model(\App\Models\LessonProgressModel::class),
        );
    }
}
