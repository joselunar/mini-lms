<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $php = $this->db->table('courses')->where('slug', 'fundamentos-de-php')->get()->getRowArray();
        $ci  = $this->db->table('courses')->where('slug', 'codeigniter-4-na-pratica')->get()->getRowArray();

        $this->db->table('lessons')->insertBatch([
            [
                'course_id'        => $php['id'],
                'title'            => 'Variáveis e tipos',
                'content'          => 'PHP é dinamicamente tipado. Comece por string, int, array e null.',
                'position'         => 1,
                'duration_minutes' => 15,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $php['id'],
                'title'            => 'Funções e organização',
                'content'          => 'Separe regras de negócio de controllers. Funções puras são fáceis de testar.',
                'position'         => 2,
                'duration_minutes' => 20,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $php['id'],
                'title'            => 'Erros e validação',
                'content'          => 'Valide entrada, lance exceções de domínio e devolva HTTP sem vazar SQL.',
                'position'         => 3,
                'duration_minutes' => 18,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $ci['id'],
                'title'            => 'Rotas e filters',
                'content'          => 'Defina rotas explícitas. Use filters para autenticação e autorização.',
                'position'         => 1,
                'duration_minutes' => 16,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'course_id'        => $ci['id'],
                'title'            => 'API REST',
                'content'          => 'JSON consistente, códigos HTTP corretos e token Bearer.',
                'position'         => 2,
                'duration_minutes' => 22,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);
    }
}
