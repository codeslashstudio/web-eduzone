<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table          = 'teachers';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'user_id', 'nip', 'nuptk', 'full_name', 'gender',
        'birth_place', 'birth_date', 'religion', 'address',
        'phone', 'email', 'last_education', 'education_major',
        'employment_status', 'joined_date', 'major_id',
        'is_homeroom', 'photo', 'is_active',
    ];

    // ============================================================
    // GET SEMUA GURU AKTIF (dengan nama jurusan & info wali kelas)
    // ============================================================
    public function getGuruAktif(string $academicYear = '2025/2026'): array
    {
        return $this->db->query("
            SELECT t.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas AS kelas_wali, c.id AS class_id_wali
            FROM teachers t
            LEFT JOIN majors m ON m.id = t.major_id
            LEFT JOIN homeroom_assignments ha ON ha.teacher_id = t.id
                AND ha.academic_year = ? AND ha.is_active = 1
            LEFT JOIN classes c ON c.id = ha.class_id
            WHERE t.is_active = 1
            ORDER BY t.full_name ASC
        ", [$academicYear])->getResultArray();
    }

    // ============================================================
    // GET DETAIL SATU GURU
    // ============================================================
    public function getGuruById(int $id, string $academicYear = '2025/2026'): array
    {
        $row = $this->db->query("
            SELECT t.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas AS kelas_wali, c.id AS class_id_wali,
                   c.grade AS kelas_grade, c.class_group AS kelas_group
            FROM teachers t
            LEFT JOIN majors m ON m.id = t.major_id
            LEFT JOIN homeroom_assignments ha ON ha.teacher_id = t.id
                AND ha.academic_year = ? AND ha.is_active = 1
            LEFT JOIN classes c ON c.id = ha.class_id
            WHERE t.id = ?
        ", [$academicYear, $id])->getRowArray();
        return $row ?? [];
    }

    // ============================================================
    // GET JADWAL GURU
    // ============================================================
    public function getJadwal(int $teacherId, ?string $day = null): array
    {
        $sql = "
            SELECT sc.*, c.nama_kelas, m.abbreviation AS major_abbr
            FROM schedules sc
            LEFT JOIN classes c ON c.id = sc.class_id
            LEFT JOIN majors m ON m.abbreviation = sc.major
            WHERE sc.teacher_id = ? AND sc.is_active = 1
        ";
        $params = [$teacherId];
        if ($day) { $sql .= " AND sc.day = ?"; $params[] = $day; }
        $sql .= " ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time";
        return $this->db->query($sql, $params)->getResultArray();
    }

    // ============================================================
    // STATISTIK
    // ============================================================
    public function countAktif(): int
    {
        return $this->where('is_active', 1)->countAllResults();
    }

    public function countWakel(): int
    {
        return $this->where('is_homeroom', 1)->where('is_active', 1)->countAllResults();
    }
}