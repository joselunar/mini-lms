<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table         = 'enrollments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'user_id',
        'course_id',
        'status',
        'progress_percent',
        'enrolled_at',
        'completed_at',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findByUserAndCourse(int $userId, int $courseId): ?array
    {
        return $this->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first() ?: null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function byUser(int $userId): array
    {
        return $this->select('enrollments.*, courses.title as course_title, courses.status as course_status')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $userId)
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();
    }
}
