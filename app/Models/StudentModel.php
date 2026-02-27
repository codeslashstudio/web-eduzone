<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table         = 'students';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id', 'nis', 'nisn', 'full_name', 'gender',
        'birth_place', 'birth_date', 'religion', 'address',
        'phone', 'email', 'grade', 'major_id', 'class_group',
        'father_name', 'mother_name', 'father_job', 'mother_job',
        'parent_address', 'parent_phone', 'joined_date',
        'status', 'photo',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get all siswa with nama jurusan (join majors)
    public function getSiswaWithMajor()
    {
        return $this->select('students.*, majors.name as major_name, majors.abbreviation as major_abbr')
                    ->join('majors', 'majors.id = students.major_id', 'left')
                    ->orderBy('students.full_name', 'ASC')
                    ->findAll();
    }

    // Get detail satu siswa dengan nama jurusan
    public function getSiswaById($id)
    {
        return $this->select('students.*, majors.name as major_name, majors.abbreviation as major_abbr')
                    ->join('majors', 'majors.id = students.major_id', 'left')
                    ->where('students.id', $id)
                    ->first();
    }

    // Get siswa by grade
    public function getSiswaByGrade($grade)
    {
        return $this->select('students.*, majors.name as major_name')
                    ->join('majors', 'majors.id = students.major_id', 'left')
                    ->where('students.grade', $grade)
                    ->where('students.status', 'aktif')
                    ->orderBy('students.full_name', 'ASC')
                    ->findAll();
    }

    // Count by status
    public function countByStatus($status = 'aktif')
    {
        return $this->where('status', $status)->countAllResults();
    }

    // Count by gender
    public function countByGender($gender)
    {
        return $this->where('gender', $gender)->countAllResults();
    }
}
