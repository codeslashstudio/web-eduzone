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
        'class_id',
        'father_name', 'mother_name', 'father_job', 'mother_job',
        'parent_address', 'parent_phone', 'joined_date',
        'status', 'photo',
    ];

    protected $validationRules = [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'gender'    => 'required|in_list[L,P]',
        'grade'     => 'required|in_list[X,XI,XII]',
        'status'    => 'required|in_list[aktif,lulus,pindah,drop_out]',
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

    // GET ALL aktif saja
    public function getSiswaAktif(): array
    {
        return $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas, c.id AS class_id
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.status = 'aktif'
            ORDER BY s.grade ASC, s.full_name ASC
        ")->getResultArray();
    }

    // ============================================================
    // GET DETAIL SATU SISWA
    // ============================================================
    public function getSiswaById(int $id): array
    {
        $row = $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas, c.id AS class_id,
                   c.grade AS kelas_grade, c.class_group AS kelas_group
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.id = ?
        ", [$id])->getRowArray();

        return $row ?? [];
    }

    // GET BY USER_ID (untuk login siswa)
    public function getSiswaByUserId(int $userId): array
    {
        $row = $this->db->query("
            SELECT s.*, m.name AS major_name, m.abbreviation AS major_abbr,
                   c.nama_kelas, c.id AS class_id
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            WHERE s.user_id = ?
        ", [$userId])->getRowArray();

        return $row ?? [];
    }

    // ============================================================
    // GET BY CLASS_ID
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
    // GET BY GRADE (legacy)
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
    // SAVE & UPDATE: auto-resolve class_id
    // ============================================================
    public function saveWithClassId(array $data, string $academicYear = '2025/2026'): bool|int
    {
        $data['class_id'] = $this->resolveClassId($data, $academicYear);

        // Set joined_date jika kosong
        if (empty($data['joined_date'])) {
            $data['joined_date'] = date('Y-m-d');
        }

        return $this->insert($data);
    }

    public function updateWithClassId(int $id, array $data, string $academicYear = '2025/2026'): bool
    {
        $data['class_id'] = $this->resolveClassId($data, $academicYear);
        return $this->update($id, $data);
    }

    // Helper: resolve class_id dari grade+major_id+class_group
    private function resolveClassId(array $data, string $academicYear): ?int
    {
        // Sudah ada class_id → pakai langsung
        if (!empty($data['class_id'])) {
            return (int)$data['class_id'];
        }

        // Cari dari grade+major_id+class_group
        if (!empty($data['grade']) && !empty($data['major_id']) && !empty($data['class_group'])) {
            $kelas = $this->db->table('classes')
                ->where('grade', $data['grade'])
                ->where('major_id', (int)$data['major_id'])
                ->where('class_group', $data['class_group'])
                ->where('academic_year', $academicYear)
                ->where('is_active', 1)
                ->get()->getRowArray();

            return $kelas ? (int)$kelas['id'] : null;
        }

        return null;
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
        return $this->where('gender', $gender)
                    ->where('status', 'aktif')
                    ->countAllResults();
    }

    public function countByClass(int $classId): int
    {
        return $this->where('class_id', $classId)
                    ->where('status', 'aktif')
                    ->countAllResults();
    }

    public function getStatsRingkasan(): array
    {
        $row = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status  = 'aktif' THEN 1 ELSE 0 END) AS aktif,
                SUM(CASE WHEN status  = 'lulus' THEN 1 ELSE 0 END) AS lulus,
                SUM(CASE WHEN gender  = 'L'     THEN 1 ELSE 0 END) AS laki,
                SUM(CASE WHEN gender  = 'P'     THEN 1 ELSE 0 END) AS perempuan,
                SUM(CASE WHEN grade   = 'X'     THEN 1 ELSE 0 END) AS kelas_x,
                SUM(CASE WHEN grade   = 'XI'    THEN 1 ELSE 0 END) AS kelas_xi,
                SUM(CASE WHEN grade   = 'XII'   THEN 1 ELSE 0 END) AS kelas_xii
            FROM students
        ")->getRowArray();

        return $row ?? [];
    }

    // Distribusi per kelas (untuk tabel/chart)
    public function getDistribusiPerKelas(string $academicYear = '2025/2026'): array
    {
        return $this->db->query("
            SELECT c.id AS class_id, c.nama_kelas, c.grade,
                   m.abbreviation AS major_abbr, c.class_group,
                   COUNT(s.id) AS jumlah,
                   SUM(CASE WHEN s.gender = 'L' THEN 1 ELSE 0 END) AS laki,
                   SUM(CASE WHEN s.gender = 'P' THEN 1 ELSE 0 END) AS perempuan
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            WHERE c.academic_year = ? AND c.is_active = 1
            GROUP BY c.id
            ORDER BY c.grade, m.abbreviation, c.class_group
        ", [$academicYear])->getResultArray();
    }
}