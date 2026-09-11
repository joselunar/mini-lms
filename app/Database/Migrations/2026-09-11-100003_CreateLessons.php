<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessons extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'duration_minutes' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 10,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('course_id');
        $this->forge->addUniqueKey(['course_id', 'position']);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lessons');
    }

    public function down(): void
    {
        $this->forge->dropTable('lessons');
    }
}
