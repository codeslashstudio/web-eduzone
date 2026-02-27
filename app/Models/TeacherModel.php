<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table         = 'teachers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id', 'nip', 'nuptk', 'full_name', 'gender',
        'birth_place', 'birth_date', 'religion', 'address',
        'phone', 'email', 'last_education', 'education_major',
        'employment_status', 'joined_date', 'major_id',
        'is_homeroom', 'photo', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get semua guru aktif dengan nama jurusan
    public function getGuruAktif()
    {
        return $this->select('teachers.*, majors.name as major_name')
                    ->join('majors', 'majors.id = teachers.major_id', 'left')
                    ->where('teachers.is_active', 1)
                    ->orderBy('teachers.full_name', 'ASC')
                    ->findAll();
    }

    // Get detail satu guru
    public function getGuruById($id)
    {
        return $this->select('teachers.*, majors.name as major_name')
                    ->join('majors', 'majors.id = teachers.major_id', 'left')
                    ->where('teachers.id', $id)
                    ->first();
    }

    // Count guru aktif
    public function countAktif()
    {
        return $this->where('is_active', 1)->countAllResults();
    }
}
