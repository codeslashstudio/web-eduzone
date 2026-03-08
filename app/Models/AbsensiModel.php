<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    // ============================================================
    // SISWA PER KELAS
    // ============================================================

    public function getSiswaByClassId(int $classId): array
    {
        return $this->db->table('students')
            ->select('id, nis, full_name, gender')
            ->where('class_id', $classId)
            ->where('status', 'aktif')
            ->orderBy('full_name', 'ASC')
            ->get()->getResultArray();
    }

    // Legacy — masih dipakai selama transisi
    public function getSiswaByKelas($grade, $major_id, $class_group): array
    {
        return $this->db->table('students')
            ->select('id, nis, full_name, gender')
            ->where('grade', $grade)
            ->where('major_id', $major_id)
            ->where('class_group', $class_group)
            ->where('status', 'aktif')
            ->orderBy('full_name', 'ASC')
            ->get()->getResultArray();
    }

    public function getKelasList(string $academicYear = '2025/2026'): array
    {
        return $this->db->query("
            SELECT c.id AS class_id, c.grade, c.major_id, c.class_group,
                   c.nama_kelas, m.abbreviation AS major_name,
                   COUNT(DISTINCT s.id) AS jumlah_siswa
            FROM classes c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            WHERE c.academic_year = ? AND c.is_active = 1
            GROUP BY c.id
            ORDER BY c.grade ASC, m.abbreviation ASC, c.class_group ASC
        ", [$academicYear])->getResultArray();
    }

    // ============================================================
    // ABSENSI HARIAN (student_attendance)
    // ============================================================

    public function getAbsensiHarianByClassId(string $date, int $classId): array
    {
        return $this->db->query("
            SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                   sa.id AS absensi_id, sa.status, sa.check_in, sa.check_out, sa.notes
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id AND sa.date = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            ORDER BY s.full_name ASC
        ", [$date, $classId])->getResultArray();
    }

    // Legacy
    public function getAbsensiHarian($date, $grade, $major_id, $class_group): array
    {
        return $this->db->query("
            SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                   sa.id AS absensi_id, sa.status, sa.check_in, sa.check_out, sa.notes
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id AND sa.date = ?
            WHERE s.grade = ? AND s.major_id = ? AND s.class_group = ?
              AND s.status = 'aktif'
            ORDER BY s.full_name ASC
        ", [$date, $grade, $major_id, $class_group])->getResultArray();
    }

    public function saveAbsensiHarian(array $rows, string $date, int $recorded_by): bool
    {
        $studentIds = array_column($rows, 'student_id');
        if (!empty($studentIds)) {
            $this->db->table('student_attendance')
                ->where('date', $date)
                ->whereIn('student_id', $studentIds)
                ->delete();
        }

        $insertData = [];
        foreach ($rows as $row) {
            $insertData[] = [
                'student_id'  => (int)$row['student_id'],
                'date'        => $date,
                'status'      => $row['status'],
                'check_in'    => !empty($row['check_in'])  ? $row['check_in']  : null,
                'check_out'   => !empty($row['check_out']) ? $row['check_out'] : null,
                'notes'       => $row['notes'] ?? null,
                'recorded_by' => $recorded_by,
            ];
        }

        return !empty($insertData)
            ? $this->db->table('student_attendance')->insertBatch($insertData) !== false
            : false;
    }

    // ============================================================
    // ABSENSI PER MAPEL (student_subject_attendance)
    // ============================================================

    // Fix: $teacher_id nullable untuk akses TU/superadmin
    public function getJadwalGuru(?int $teacher_id, ?string $day = null): array
    {
        $builder = $this->db->table('schedules sc')
            ->select('sc.*, t.full_name AS nama_guru, c.nama_kelas,
                      c.id AS class_id, m.abbreviation AS major_name')
            ->join('teachers t', 't.id = sc.teacher_id', 'left')
            ->join('classes c', 'c.id = sc.class_id', 'left')
            ->join('majors m', 'm.abbreviation = sc.major', 'left')
            ->where('sc.is_active', 1);

        if ($teacher_id !== null) {
            $builder->where('sc.teacher_id', $teacher_id);
        }

        if ($day) {
            $builder->where('sc.day', $day);
        }

        return $builder
            ->orderBy('sc.day')
            ->orderBy('sc.start_time', 'ASC')
            ->get()->getResultArray();
    }

    public function getOrCreateTeachingAttendance(
        int $schedule_id,
        ?int $teacher_id,
        string $date,
        ?string $topic = null
    ): int {
        $builder = $this->db->table('teaching_attendance')
            ->where('schedule_id', $schedule_id)
            ->where('date', $date);

        if ($teacher_id !== null) {
            $builder->where('teacher_id', $teacher_id);
        }

        $existing = $builder->get()->getRowArray();
        if ($existing) return (int)$existing['id'];

        $schedule = $this->db->table('schedules')
            ->where('id', $schedule_id)
            ->get()->getRowArray();

        $this->db->table('teaching_attendance')->insert([
            'schedule_id' => $schedule_id,
            'teacher_id'  => $teacher_id,
            'date'        => $date,
            'start_time'  => $schedule['start_time'] ?? date('H:i:s'),
            'end_time'    => $schedule['end_time']   ?? date('H:i:s'),
            'topic'       => $topic,
        ]);

        return (int)$this->db->insertID();
    }

    public function getAbsensiMapel(int $teaching_attendance_id, int $schedule_id, string $date): array
    {
        $schedule = $this->db->table('schedules')
            ->where('id', $schedule_id)
            ->get()->getRowArray();

        if (!$schedule) return [];

        // Pakai class_id jika sudah ada
        if (!empty($schedule['class_id'])) {
            return $this->db->query("
                SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                       ssa.id AS absensi_id, ssa.status, ssa.notes
                FROM students s
                LEFT JOIN student_subject_attendance ssa
                    ON s.id = ssa.student_id
                    AND ssa.teaching_attendance_id = ?
                    AND ssa.date = ?
                WHERE s.class_id = ? AND s.status = 'aktif'
                ORDER BY s.full_name ASC
            ", [$teaching_attendance_id, $date, $schedule['class_id']])->getResultArray();
        }

        // Legacy fallback
        $major    = $this->db->table('majors')
            ->where('abbreviation', $schedule['major'])
            ->get()->getRowArray();
        $major_id = $major['id'] ?? null;

        return $this->db->query("
            SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                   ssa.id AS absensi_id, ssa.status, ssa.notes
            FROM students s
            LEFT JOIN student_subject_attendance ssa
                ON s.id = ssa.student_id
                AND ssa.teaching_attendance_id = ?
                AND ssa.date = ?
            WHERE s.grade = ? AND s.major_id = ? AND s.class_group = ?
              AND s.status = 'aktif'
            ORDER BY s.full_name ASC
        ", [$teaching_attendance_id, $date,
            $schedule['grade'], $major_id, $schedule['class_group']]
        )->getResultArray();
    }

    public function saveAbsensiMapel(
        array $rows,
        int $teaching_attendance_id,
        int $schedule_id,
        string $date
    ): bool {
        $studentIds = array_column($rows, 'student_id');
        if (!empty($studentIds)) {
            $this->db->table('student_subject_attendance')
                ->where('teaching_attendance_id', $teaching_attendance_id)
                ->where('date', $date)
                ->whereIn('student_id', $studentIds)
                ->delete();
        }

        $insertData = [];
        foreach ($rows as $row) {
            $insertData[] = [
                'teaching_attendance_id' => $teaching_attendance_id,
                'student_id'             => (int)$row['student_id'],
                'schedule_id'            => $schedule_id,
                'date'                   => $date,
                'status'                 => $row['status'],
                'notes'                  => $row['notes'] ?? null,
            ];
        }

        return !empty($insertData)
            ? $this->db->table('student_subject_attendance')->insertBatch($insertData) !== false
            : false;
    }

    // ============================================================
    // REKAP & STATISTIK — semua pakai prepared statement
    // ============================================================

    public function getRekapBulananByClassId(int $classId, string $bulan, string $tahun): array
    {
        return $this->db->query("
            SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS alpa,
                COUNT(sa.id) AS total_hari
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id
                AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            GROUP BY s.id ORDER BY s.full_name ASC
        ", [$bulan, $tahun, $classId])->getResultArray();
    }

    // Legacy
    public function getRekapBulanan($grade, $major_id, $class_group, $bulan, $tahun): array
    {
        return $this->db->query("
            SELECT s.id AS student_id, s.nis, s.full_name, s.gender,
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS alpa,
                COUNT(sa.id) AS total_hari
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id
                AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            WHERE s.grade = ? AND s.major_id = ? AND s.class_group = ?
              AND s.status = 'aktif'
            GROUP BY s.id ORDER BY s.full_name ASC
        ", [$bulan, $tahun, $grade, $major_id, $class_group])->getResultArray();
    }

    public function getStatistikKehadiranByClassId(int $classId, string $bulan, string $tahun): array
    {
        return $this->db->query("
            SELECT
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS total_hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS total_sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS total_izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS total_alpa,
                COUNT(sa.id) AS total_records,
                COUNT(DISTINCT s.id) AS total_siswa
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id
                AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
        ", [$bulan, $tahun, $classId])->getRowArray() ?? [];
    }

    // Fix: pakai prepared statement, bukan string interpolation
    public function getStatistikKehadiran(
        ?string $grade = null,
        ?int $major_id = null,
        ?string $class_group = null,
        ?string $bulan = null,
        ?string $tahun = null
    ): array {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $builder = $this->db->table('students s')
            ->select("
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS total_hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS total_sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS total_izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS total_alpa,
                COUNT(sa.id) AS total_records,
                COUNT(DISTINCT s.id) AS total_siswa
            ")
            ->join('student_attendance sa',
                "s.id = sa.student_id AND MONTH(sa.date) = $bulan AND YEAR(sa.date) = $tahun",
                'left')
            ->where('s.status', 'aktif');

        if ($grade)       $builder->where('s.grade', $grade);
        if ($major_id)    $builder->where('s.major_id', $major_id);
        if ($class_group) $builder->where('s.class_group', $class_group);

        return $builder->get()->getRowArray() ?? [];
    }

    public function getAbsensiTrendByClassId(int $classId, string $bulan, string $tahun): array
    {
        return $this->db->query("
            SELECT DAY(sa.date) AS hari, sa.date,
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            WHERE s.class_id = ? AND s.status = 'aktif'
              AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            GROUP BY sa.date ORDER BY sa.date ASC
        ", [$classId, $bulan, $tahun])->getResultArray();
    }

    // Fix: pakai query builder bukan string interpolation
    public function getAbsensiTrend(
        ?string $grade = null,
        ?int $major_id = null,
        ?string $class_group = null,
        ?string $bulan = null,
        ?string $tahun = null
    ): array {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $builder = $this->db->table('students s')
            ->select('DAY(sa.date) AS hari, sa.date,
                COUNT(CASE WHEN sa.status = \'Hadir\' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = \'Alpa\'  THEN 1 END) AS alpa')
            ->join('student_attendance sa',
                "s.id = sa.student_id AND MONTH(sa.date) = $bulan AND YEAR(sa.date) = $tahun")
            ->where('s.status', 'aktif')
            ->groupBy('sa.date')
            ->orderBy('sa.date', 'ASC');

        if ($grade)       $builder->where('s.grade', $grade);
        if ($major_id)    $builder->where('s.major_id', $major_id);
        if ($class_group) $builder->where('s.class_group', $class_group);

        return $builder->get()->getResultArray();
    }

    public function getSiswaAlpaTerbanyakByClassId(
        int $classId,
        string $bulan,
        string $tahun,
        int $limit = 10
    ): array {
        return $this->db->query("
            SELECT s.id, s.nis, s.full_name, s.gender,
                   c.nama_kelas, COUNT(sa.id) AS total_alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            JOIN classes c ON s.class_id = c.id
            WHERE s.class_id = ? AND s.status = 'aktif'
              AND sa.status = 'Alpa'
              AND MONTH(sa.date) = ? AND YEAR(sa.date) = ?
            GROUP BY s.id
            ORDER BY total_alpa DESC
            LIMIT $limit
        ", [$classId, $bulan, $tahun])->getResultArray();
    }

    // Fix: pakai prepared statement
    public function getSiswaAlpaTerbanyak(
        ?string $grade = null,
        ?int $major_id = null,
        ?string $bulan = null,
        ?string $tahun = null,
        int $limit = 10
    ): array {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $builder = $this->db->table('students s')
            ->select("s.id, s.nis, s.full_name, s.grade,
                      m.abbreviation AS major_name, s.class_group,
                      COUNT(sa.id) AS total_alpa")
            ->join('student_attendance sa', 's.id = sa.student_id')
            ->join('majors m', 's.major_id = m.id')
            ->where('sa.status', 'Alpa')
            ->where('s.status', 'aktif')
            ->where("MONTH(sa.date)", $bulan)
            ->where("YEAR(sa.date)", $tahun)
            ->groupBy('s.id')
            ->orderBy('total_alpa', 'DESC')
            ->limit($limit);

        if ($grade)    $builder->where('s.grade', $grade);
        if ($major_id) $builder->where('s.major_id', $major_id);

        return $builder->get()->getResultArray();
    }
}