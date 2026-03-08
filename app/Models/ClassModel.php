<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table      = 'classes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'grade', 'major_id', 'class_group', 'academic_year',
        'nama_kelas', 'kapasitas', 'is_active',
    ];

    // ============================================================
    // GET ALL KELAS dengan info jurusan
    // ============================================================
    public function getAll(string $academicYear = '2025/2026', bool $activeOnly = true): array
    {
        $q = $this->db->query("
            SELECT c.*, m.abbreviation AS major_abbr, m.name AS major_name,
                   COUNT(DISTINCT s.id) AS jumlah_siswa,
                   t.full_name AS nama_wakel
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.academic_year = c.academic_year AND ha.is_active = 1
            LEFT JOIN teachers t ON ha.teacher_id = t.id
            WHERE c.academic_year = ?
            " . ($activeOnly ? "AND c.is_active = 1" : "") . "
            GROUP BY c.id
            ORDER BY c.grade, m.abbreviation, c.class_group
        ", [$academicYear]);
        return $q->getResultArray();
    }

    // ============================================================
    // GET SATU KELAS BY ID
    // ============================================================
    public function getById(int $id): array
    {
        $row = $this->db->query("
            SELECT c.*, m.abbreviation AS major_abbr, m.name AS major_name,
                   COUNT(DISTINCT s.id) AS jumlah_siswa,
                   ha.teacher_id AS wakel_id, t.full_name AS nama_wakel
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.is_active = 1
            LEFT JOIN teachers t ON ha.teacher_id = t.id
            WHERE c.id = ?
            GROUP BY c.id
        ", [$id])->getRowArray();
        return $row ?? [];
    }

    // ============================================================
    // GET KELAS DARI TEACHER (wali kelas)
    // ============================================================
    public function getByTeacher(int $teacherId, string $academicYear = '2025/2026'): array
    {
        $row = $this->db->query("
            SELECT c.*, m.abbreviation AS major_abbr, m.name AS major_name,
                   COUNT(DISTINCT s.id) AS jumlah_siswa
            FROM homeroom_assignments ha
            JOIN classes c ON ha.class_id = c.id
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            WHERE ha.teacher_id = ? AND ha.academic_year = ? AND ha.is_active = 1
            GROUP BY c.id
            LIMIT 1
        ", [$teacherId, $academicYear])->getRowArray();
        return $row ?? [];
    }

    // ============================================================
    // GET KELAS DARI SISWA
    // ============================================================
    public function getByStudent(int $studentId): array
    {
        $row = $this->db->query("
            SELECT c.*, m.abbreviation AS major_abbr, m.name AS major_name
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN majors m ON c.major_id = m.id
            WHERE s.id = ?
        ", [$studentId])->getRowArray();
        return $row ?? [];
    }

    // ============================================================
    // GET SISWA DI KELAS
    // ============================================================
    public function getSiswa(int $classId, string $status = 'aktif'): array
    {
        return $this->db->query("
            SELECT s.*, m.abbreviation AS major_abbr
            FROM students s
            JOIN majors m ON s.major_id = m.id
            WHERE s.class_id = ? AND s.status = ?
            ORDER BY s.full_name ASC
        ", [$classId, $status])->getResultArray();
    }

    // ============================================================
    // GET JADWAL KELAS
    // ============================================================
    public function getJadwal(int $classId, ?string $day = null): array
    {
        $sql = "
            SELECT sc.*, t.full_name AS nama_guru
            FROM schedules sc
            LEFT JOIN teachers t ON sc.teacher_id = t.id
            WHERE sc.class_id = ? AND sc.is_active = 1
        ";
        $params = [$classId];
        if ($day) {
            $sql .= " AND sc.day = ?";
            $params[] = $day;
        }
        $sql .= " ORDER BY FIELD(sc.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), sc.start_time";
        return $this->db->query($sql, $params)->getResultArray();
    }

    // ============================================================
    // ASSIGN WALI KELAS
    // ============================================================
    public function assignWakel(int $classId, int $teacherId, string $academicYear = '2025/2026'): bool
    {
        // Hapus assignment lama untuk kelas ini di tahun yang sama
        $this->db->query("
            DELETE FROM homeroom_assignments
            WHERE class_id = ? AND academic_year = ?
        ", [$classId, $academicYear]);

        // Insert baru
        $this->db->table('homeroom_assignments')->insert([
            'teacher_id'    => $teacherId,
            'class_id'      => $classId,
            'academic_year' => $academicYear,
            'is_active'     => 1,
        ]);

        // Update is_homeroom di teachers
        $this->db->query("UPDATE teachers SET is_homeroom = 1 WHERE id = ?", [$teacherId]);

        return true;
    }

    // ============================================================
    // STATISTIK KELAS (untuk dashboard)
    // ============================================================
    public function getStatistik(int $classId, string $bulan, string $tahun): array
    {
        $stat = $this->db->query("
            SELECT
                COUNT(DISTINCT s.id) AS total_siswa,
                SUM(CASE WHEN sa.status = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
                SUM(CASE WHEN sa.status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
                SUM(CASE WHEN sa.status = 'Izin'  THEN 1 ELSE 0 END) AS total_izin,
                SUM(CASE WHEN sa.status = 'Alpa'  THEN 1 ELSE 0 END) AS total_alpa,
                COUNT(DISTINCT sa.date) AS total_hari
            FROM students s
            LEFT JOIN student_attendance sa ON s.id = sa.student_id
                AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
        ", [$bulan, $tahun, $classId])->getRowArray();
        return $stat ?? [];
    }

    // ============================================================
    // NAIK KELAS: clone semua kelas ke tahun ajaran baru
    // ============================================================
    public function cloneToNewYear(string $oldYear, string $newYear): int
    {
        $classes = $this->where('academic_year', $oldYear)
                        ->where('is_active', 1)
                        ->findAll();
        $inserted = 0;
        foreach ($classes as $c) {
            // Skip kelas XII (sudah lulus)
            if ($c['grade'] === 'XII') continue;

            $newGrade = match($c['grade']) {
                'X'  => 'XI',
                'XI' => 'XII',
                default => null,
            };
            if (!$newGrade) continue;

            // Cek sudah ada belum
            $exists = $this->where('grade', $newGrade)
                           ->where('major_id', $c['major_id'])
                           ->where('class_group', $c['class_group'])
                           ->where('academic_year', $newYear)
                           ->first();
            if ($exists) continue;

            $this->insert([
                'grade'         => $newGrade,
                'major_id'      => $c['major_id'],
                'class_group'   => $c['class_group'],
                'academic_year' => $newYear,
                'nama_kelas'    => str_replace($c['grade'], $newGrade, $c['nama_kelas']),
                'kapasitas'     => $c['kapasitas'],
                'is_active'     => 1,
            ]);
            $inserted++;
        }
        return $inserted;
    }
}