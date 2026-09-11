<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(LessonSeeder::class);
        $this->call(EnrollmentSeeder::class);
    }
}
