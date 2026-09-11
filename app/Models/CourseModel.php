<?php

namespace App\Models;

use App\Domain\CourseStatus;
use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table         = 'courses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['title', 'slug', 'description', 'workload_hours', 'status'];

    protected $validationRules = [
        'title'           => 'required|min_length[3]|max_length[180]',
        'slug'            => 'required|min_length[3]|max_length[180]|is_unique[courses.slug,id,{id}]',
        'description'     => 'permit_empty',
        'workload_hours'  => 'required|integer|greater_than_equal_to[0]',
        'status'          => 'required|in_list[rascunho,publicado,encerrado]',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function published(): array
    {
        return $this->where('status', CourseStatus::PUBLISHED)
            ->orderBy('title', 'ASC')
            ->findAll();
    }
}
