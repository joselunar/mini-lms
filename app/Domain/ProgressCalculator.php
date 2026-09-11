<?php

namespace App\Domain;

class ProgressCalculator
{
    public function percent(int $completedLessons, int $totalLessons): int
    {
        if ($totalLessons <= 0) {
            return 0;
        }

        return (int) floor(($completedLessons / $totalLessons) * 100);
    }

    public function isCourseComplete(int $completedLessons, int $totalLessons): bool
    {
        return $totalLessons > 0 && $completedLessons >= $totalLessons;
    }
}
