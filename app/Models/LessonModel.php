<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $table         = 'lessons';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['course_id', 'title', 'content', 'position', 'duration_minutes'];

    protected $validationRules = [
        'course_id'         => 'required|integer',
        'title'             => 'required|min_length[3]|max_length[180]',
        'content'           => 'permit_empty',
        'position'          => 'required|integer|greater_than[0]',
        'duration_minutes'  => 'required|integer|greater_than[0]',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function byCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId)
            ->orderBy('position', 'ASC')
            ->findAll();
    }

    public function countByCourse(int $courseId): int
    {
        return $this->where('course_id', $courseId)->countAllResults();
    }

    public function nextPosition(int $courseId): int
    {
        $last = $this->where('course_id', $courseId)
            ->orderBy('position', 'DESC')
            ->first();

        return $last ? ((int) $last['position'] + 1) : 1;
    }
}
