<?php

namespace App\Controllers;

use App\Domain\DomainException;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use App\Models\LessonProgressModel;
use App\Services\CourseService;
use App\Services\EnrollmentService;
use App\Services\ProgressService;

class Catalog extends BaseController
{
    public function index()
    {
        return view('catalog/index', [
            'title'   => 'Cursos',
            'courses' => $this->courses()->catalog(),
        ]);
    }

    public function show(int $id)
    {
        try {
            $course = $this->courses()->courseForStudent($id, service('currentUser')->id());
        } catch (DomainException $exception) {
            return redirect()->to('/')->with('error', $exception->getMessage());
        }

        return view('catalog/show', [
            'title'  => $course['title'],
            'course' => $course,
        ]);
    }

    public function enroll(int $id)
    {
        try {
            $this->enrollments()->enroll(service('currentUser')->id(), $id);
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('cursos/' . $id)->with('success', 'Matrícula realizada.');
    }

    public function lesson(int $courseId, int $lessonId)
    {
        try {
            $course = $this->courses()->courseForStudent($courseId, service('currentUser')->id());
            $this->enrollments()->requireEnrollment(service('currentUser')->id(), $courseId);
        } catch (DomainException $exception) {
            return redirect()->to('cursos/' . $courseId)->with('error', $exception->getMessage());
        }

        $lesson = null;
        foreach ($course['lessons'] as $item) {
            if ((int) $item['id'] === $lessonId) {
                $lesson = $item;
                break;
            }
        }

        if ($lesson === null) {
            return redirect()->to('cursos/' . $courseId)->with('error', 'Aula não encontrada neste curso.');
        }

        return view('catalog/lesson', [
            'title'  => $lesson['title'],
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    public function complete(int $courseId, int $lessonId)
    {
        try {
            $this->progress()->completeLesson(service('currentUser')->id(), $lessonId);
        } catch (DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->to('cursos/' . $courseId . '/aulas/' . $lessonId)->with('success', 'Aula concluída.');
    }

    public function enrollments()
    {
        return view('catalog/enrollments', [
            'title'       => 'Minhas matrículas',
            'enrollments' => $this->enrollments()->listForUser(service('currentUser')->id()),
        ]);
    }

    private function courses(): CourseService
    {
        return new CourseService(model(CourseModel::class), model(LessonModel::class), model(LessonProgressModel::class));
    }

    private function enrollments(): EnrollmentService
    {
        return new EnrollmentService(model(CourseModel::class), model(LessonModel::class), model(EnrollmentModel::class));
    }

    private function progress(): ProgressService
    {
        return new ProgressService(
            model(CourseModel::class),
            model(LessonModel::class),
            model(EnrollmentModel::class),
            model(LessonProgressModel::class),
            $this->enrollments(),
        );
    }
}
