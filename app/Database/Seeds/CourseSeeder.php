<?php

namespace App\Database\Seeds;

use App\Domain\CourseStatus;
use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('courses')->insertBatch([
            [
                'title'          => 'Fundamentos de PHP',
                'slug'           => 'fundamentos-de-php',
                'description'    => 'Sintaxe, tipos, arrays e organização de um script PHP.',
                'workload_hours' => 8,
                'status'         => CourseStatus::PUBLISHED,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'CodeIgniter 4 na prática',
                'slug'           => 'codeigniter-4-na-pratica',
                'description'    => 'Rotas, controllers, models e uma API REST enxuta.',
                'workload_hours' => 12,
                'status'         => CourseStatus::PUBLISHED,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'MySQL avançado',
                'slug'           => 'mysql-avancado',
                'description'    => 'Índices, transações e modelagem. Ainda em preparação.',
                'workload_hours' => 10,
                'status'         => CourseStatus::DRAFT,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}
