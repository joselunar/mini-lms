<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $aluno = $this->db->table('users')->where('email', 'aluno@lms.local')->get()->getRowArray();
        $php   = $this->db->table('courses')->where('slug', 'fundamentos-de-php')->get()->getRowArray();
        $aula1 = $this->db->table('lessons')->where('course_id', $php['id'])->where('position', 1)->get()->getRowArray();

        $this->db->table('enrollments')->insert([
            'user_id'          => $aluno['id'],
            'course_id'        => $php['id'],
            'status'           => 'em_andamento',
            'progress_percent' => 33,
            'enrolled_at'      => date('Y-m-d H:i:s'),
        ]);

        $enrollmentId = $this->db->insertID();

        $this->db->table('lesson_progress')->insert([
            'enrollment_id' => $enrollmentId,
            'lesson_id'     => $aula1['id'],
            'completed_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
