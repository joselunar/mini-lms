<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'name'       => 'Administrador',
                'email'      => 'admin@lms.local',
                'password'   => password_hash('Admin@123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'api_token'  => bin2hex(random_bytes(32)),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Ana Aluna',
                'email'      => 'aluno@lms.local',
                'password'   => password_hash('Aluno@123', PASSWORD_DEFAULT),
                'role'       => 'aluno',
                'api_token'  => bin2hex(random_bytes(32)),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
