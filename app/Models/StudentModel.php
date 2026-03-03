<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table          = 'students';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'user_id', 'nis', 'nisn', 'full_name', 'gender',
        'birth_place', 'birth_date', 'religion', 'address',
        'phone', 'email', 'grade', 'major_id', 'class_group',
        'class_id',  // NEW: FK ke classes
        'father_name', 'mother_name', 'father_job', 'mother_job',
        'parent_address', 'parent_phone', 'joined_date',
        'status', 'photo',
    ];

    // ============================================================
    // GET ALL dengan info kelas lengkap
    // ============================================================
    public function getSiswaWithMajor(): array
    {
        return $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas, c.id AS class_id
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            ORDER BY s.full_name ASC
        ")->getResultArray();
    }

    // ============================================================
    // GET DETAIL SATU SISWA
    // ============================================================
    public function getSiswaById(int $id): array
    {
        $row = $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas, c.id AS class_id, c.grade AS kelas_grade,
                   c.class_group AS kelas_group
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.id = ?
        ", [$id])->getRowArray();
        return $row ?? [];
    }

    // ============================================================
    // GET SISWA BY CLASS_ID (NEW)
    // ============================================================
    public function getSiswaByClassId(int $classId, string $status = 'aktif'): array
    {
        return $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.class_id = ? AND s.status = ?
            ORDER BY s.full_name ASC
        ", [$classId, $status])->getResultArray();
    }

    // ============================================================
    // GET SISWA BY GRADE (legacy, masih dipakai di beberapa tempat)
    // ============================================================
    public function getSiswaByGrade(string $grade): array
    {
        return $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.grade = ? AND s.status = 'aktif'
            ORDER BY s.full_name ASC
        ", [$grade])->getResultArray();
    }

    // ============================================================
    // SAVE: auto-resolve class_id dari grade+major_id+class_group
    // ============================================================
    public function saveWithClassId(array $data, string $academicYear = '2025/2026'): bool|int
    {
        // Jika ada grade+major_id+class_group tapi tidak ada class_id, auto-resolve
        if (empty($data['class_id']) && !empty($data['grade']) && !empty($data['major_id']) && !empty($data['class_group'])) {
            $db    = \Config\Database::connect();
            $kelas = $db->table('classes')
                ->where('grade', $data['grade'])
                ->where('major_id', $data['major_id'])
                ->where('class_group', $data['class_group'])
                ->where('academic_year', $academicYear)
                ->get()->getRowArray();

            if ($kelas) {
                $data['class_id'] = $kelas['id'];
            }
        }

        return $this->insert($data);
    }

    public function updateWithClassId(int $id, array $data, string $academicYear = '2025/2026'): bool
    {
        if (empty($data['class_id']) && !empty($data['grade']) && !empty($data['major_id']) && !empty($data['class_group'])) {
            $db    = \Config\Database::connect();
            $kelas = $db->table('classes')
                ->where('grade', $data['grade'])
                ->where('major_id', $data['major_id'])
                ->where('class_group', $data['class_group'])
                ->where('academic_year', $academicYear)
                ->get()->getRowArray();

            if ($kelas) {
                $data['class_id'] = $kelas['id'];
            }
        }

        return $this->update($id, $data);
    }

    // ============================================================
    // STATISTIK
    // ============================================================
    public function countByStatus(string $status = 'aktif'): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function countByGender(string $gender): int
    {
        return $this->where('gender', $gender)->countAllResults();
    }

    public function countByClass(int $classId): int
    {
        return $this->where('class_id', $classId)->where('status', 'aktif')->countAllResults();
    }
}