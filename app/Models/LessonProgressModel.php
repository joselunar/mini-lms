<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonProgressModel extends Model
{
    protected $table         = 'lesson_progress';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['enrollment_id', 'lesson_id', 'completed_at'];

    public function alreadyCompleted(int $enrollmentId, int $lessonId): bool
    {
        return $this->where('enrollment_id', $enrollmentId)
            ->where('lesson_id', $lessonId)
            ->countAllResults() > 0;
    }

    public function countCompleted(int $enrollmentId): int
    {
        return $this->where('enrollment_id', $enrollmentId)->countAllResults();
    }

    /**
     * @return list<int>
     */
    public function completedLessonIds(int $enrollmentId): array
    {
        return array_map(
            static fn (array $row): int => (int) $row['lesson_id'],
            $this->select('lesson_id')->where('enrollment_id', $enrollmentId)->findAll(),
        );
    }
}
