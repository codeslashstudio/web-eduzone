<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    // ==========================================
    // SISWA PER KELAS
    // Untuk dropdown filter dan load daftar siswa
    // ==========================================

    public function getSiswaByKelas($grade, $major_id, $class_group)
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

    public function getKelasList()
    {
        return $this->db->query("
            SELECT DISTINCT
                s.grade,
                s.major_id,
                s.class_group,
                m.abbreviation AS major_name,
                CONCAT(s.grade, ' ', m.abbreviation, ' ', s.class_group) AS nama_kelas,
                COUNT(s.id) AS jumlah_siswa
            FROM students s
            JOIN majors m ON s.major_id = m.id
            WHERE s.status = 'aktif'
            GROUP BY s.grade, s.major_id, s.class_group
            ORDER BY s.grade ASC, m.abbreviation ASC, s.class_group ASC
        ")->getResultArray();
    }

    // ==========================================
    // ABSENSI HARIAN (student_attendance)
    // Input oleh Wali Kelas
    // ==========================================

    public function getAbsensiHarian($date, $grade, $major_id, $class_group)
    {
        return $this->db->query("
            SELECT
                s.id AS student_id,
                s.nis,
                s.full_name,
                s.gender,
                sa.id          AS absensi_id,
                sa.status,
                sa.check_in,
                sa.check_out,
                sa.notes
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id AND sa.date = ?
            WHERE s.grade       = ?
              AND s.major_id    = ?
              AND s.class_group = ?
              AND s.status      = 'aktif'
            ORDER BY s.full_name ASC
        ", [$date, $grade, $major_id, $class_group])->getResultArray();
    }

    public function saveAbsensiHarian(array $rows, string $date, int $recorded_by): bool
    {
        // Hapus data hari ini untuk kelas ini dulu (bulk replace)
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
                'student_id'  => $row['student_id'],
                'date'        => $date,
                'status'      => $row['status'],
                'check_in'    => $row['check_in']   ?? null,
                'check_out'   => $row['check_out']  ?? null,
                'notes'       => $row['notes']      ?? null,
                'recorded_by' => $recorded_by,
            ];
        }

        return $this->db->table('student_attendance')->insertBatch($insertData) !== false;
    }

    public function getAbsensiHarianById($id)
    {
        return $this->db->table('student_attendance')
            ->where('id', $id)
            ->get()->getRowArray();
    }

    public function updateAbsensiHarian($id, array $data): bool
    {
        return $this->db->table('student_attendance')
            ->where('id', $id)
            ->update($data);
    }

    // ==========================================
    // ABSENSI PER MAPEL (student_subject_attendance)
    // Input oleh Guru Mapel
    // ==========================================

    public function getJadwalGuru($teacher_id, $day = null)
    {
        $builder = $this->db->table('schedules')
            ->select('schedules.*, teachers.full_name AS nama_guru, majors.abbreviation AS major_name')
            ->join('teachers', 'teachers.id = schedules.teacher_id')
            ->join('majors', "majors.abbreviation = schedules.major", 'left')
            ->where('schedules.teacher_id', $teacher_id)
            ->where('schedules.is_active', 1);

        if ($day) {
            $builder->where('schedules.day', $day);
        }

        return $builder->orderBy('schedules.start_time', 'ASC')->get()->getResultArray();
    }

    public function getOrCreateTeachingAttendance($schedule_id, $teacher_id, $date, $topic = null)
    {
        // Cek apakah sesi sudah ada
        $existing = $this->db->table('teaching_attendance')
            ->where('schedule_id', $schedule_id)
            ->where('teacher_id', $teacher_id)
            ->where('date', $date)
            ->get()->getRowArray();

        if ($existing) {
            return $existing['id'];
        }

        // Ambil jadwal untuk start_time dan end_time
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

        return $this->db->insertID();
    }

    public function getAbsensiMapel($teaching_attendance_id, $schedule_id, $date)
    {
        // Ambil info jadwal untuk tahu kelas mana
        $schedule = $this->db->table('schedules')
            ->where('id', $schedule_id)
            ->get()->getRowArray();

        if (!$schedule) return [];

        // Ambil jurusan id dari abbreviation
        $major = $this->db->table('majors')
            ->where('abbreviation', $schedule['major'])
            ->get()->getRowArray();

        $major_id = $major['id'] ?? null;

        return $this->db->query("
            SELECT
                s.id            AS student_id,
                s.nis,
                s.full_name,
                s.gender,
                ssa.id          AS absensi_id,
                ssa.status,
                ssa.notes
            FROM students s
            LEFT JOIN student_subject_attendance ssa
                ON s.id = ssa.student_id
                AND ssa.teaching_attendance_id = ?
                AND ssa.date = ?
            WHERE s.grade       = ?
              AND s.major_id    = ?
              AND s.class_group = ?
              AND s.status      = 'aktif'
            ORDER BY s.full_name ASC
        ", [
            $teaching_attendance_id,
            $date,
            $schedule['grade'],
            $major_id,
            $schedule['class_group'],
        ])->getResultArray();
    }

    public function saveAbsensiMapel(array $rows, $teaching_attendance_id, $schedule_id, string $date): bool
    {
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
                'student_id'             => $row['student_id'],
                'schedule_id'            => $schedule_id,
                'date'                   => $date,
                'status'                 => $row['status'],
                'notes'                  => $row['notes'] ?? null,
            ];
        }

        return $this->db->table('student_subject_attendance')->insertBatch($insertData) !== false;
    }

    // ==========================================
    // REKAP ABSENSI
    // ==========================================

    public function getRekapBulanan($grade, $major_id, $class_group, $bulan, $tahun)
    {
        return $this->db->query("
            SELECT
                s.id AS student_id,
                s.nis,
                s.full_name,
                s.gender,
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS alpa,
                COUNT(sa.id) AS total_hari
            FROM students s
            LEFT JOIN student_attendance sa
                ON s.id = sa.student_id
                AND MONTH(sa.date) = ?
                AND YEAR(sa.date)  = ?
            WHERE s.grade       = ?
              AND s.major_id    = ?
              AND s.class_group = ?
              AND s.status      = 'aktif'
            GROUP BY s.id
            ORDER BY s.full_name ASC
        ", [$bulan, $tahun, $grade, $major_id, $class_group])->getResultArray();
    }

    public function getStatistikKehadiran($grade = null, $major_id = null, $class_group = null, $bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $where = "MONTH(sa.date) = $bulan AND YEAR(sa.date) = $tahun AND s.status = 'aktif'";
        if ($grade)       $where .= " AND s.grade = '$grade'";
        if ($major_id)    $where .= " AND s.major_id = $major_id";
        if ($class_group) $where .= " AND s.class_group = '$class_group'";

        return $this->db->query("
            SELECT
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS total_hadir,
                COUNT(CASE WHEN sa.status = 'Sakit' THEN 1 END) AS total_sakit,
                COUNT(CASE WHEN sa.status = 'Izin'  THEN 1 END) AS total_izin,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS total_alpa,
                COUNT(sa.id) AS total_records,
                COUNT(DISTINCT s.id) AS total_siswa
            FROM students s
            LEFT JOIN student_attendance sa ON s.id = sa.student_id
            WHERE $where
        ")->getRowArray();
    }

    public function getAbsensiTrend($grade = null, $major_id = null, $class_group = null, $bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $where = "MONTH(sa.date) = $bulan AND YEAR(sa.date) = $tahun AND s.status = 'aktif'";
        if ($grade)       $where .= " AND s.grade = '$grade'";
        if ($major_id)    $where .= " AND s.major_id = $major_id";
        if ($class_group) $where .= " AND s.class_group = '$class_group'";

        return $this->db->query("
            SELECT
                DAY(sa.date) AS hari,
                sa.date,
                COUNT(CASE WHEN sa.status = 'Hadir' THEN 1 END) AS hadir,
                COUNT(CASE WHEN sa.status = 'Alpa'  THEN 1 END) AS alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            WHERE $where
            GROUP BY sa.date
            ORDER BY sa.date ASC
        ")->getResultArray();
    }

    // Siswa dengan absensi terbanyak (alpha)
    public function getSiswaAlpaTerbanyak($grade = null, $major_id = null, $bulan = null, $tahun = null, $limit = 10)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        $where = "MONTH(sa.date) = $bulan AND YEAR(sa.date) = $tahun AND s.status = 'aktif' AND sa.status = 'Alpa'";
        if ($grade)    $where .= " AND s.grade = '$grade'";
        if ($major_id) $where .= " AND s.major_id = $major_id";

        return $this->db->query("
            SELECT
                s.id,
                s.nis,
                s.full_name,
                s.grade,
                m.abbreviation AS major_name,
                s.class_group,
                COUNT(sa.id) AS total_alpa
            FROM students s
            JOIN student_attendance sa ON s.id = sa.student_id
            JOIN majors m ON s.major_id = m.id
            WHERE $where
            GROUP BY s.id
            ORDER BY total_alpa DESC
            LIMIT $limit
        ")->getResultArray();
    }
}