<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAttendanceModel extends Model
{
    protected $table            = 'student_attendance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'student_id', 'date', 'check_in', 'check_out',
        'status', 'notes', 'recorded_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'student_id' => 'required|integer',
        'date'       => 'required|valid_date',
        'status'     => 'required|in_list[Hadir,Izin,Sakit,Alpa]',
    ];

    // Ambil semua presensi siswa dengan join ke tabel students
    public function getAttendanceWithStudent($date = null, $grade = null, $major_id = null, $class_group = null)
    {
        $builder = $this->db->table('student_attendance sa')
            ->select('sa.*, s.full_name, s.nis, s.grade, s.class_group, m.abbreviation as major')
            ->join('students s', 's.id = sa.student_id')
            ->join('majors m', 'm.id = s.major_id', 'left')
            ->orderBy('s.full_name', 'ASC');

        if ($date) $builder->where('sa.date', $date);
        if ($grade) $builder->where('s.grade', $grade);
        if ($major_id) $builder->where('s.major_id', $major_id);
        if ($class_group) $builder->where('s.class_group', $class_group);

        return $builder->get()->getResultArray();
    }

    // Cek apakah siswa sudah absen hari ini
    public function isAlreadyRecorded($student_id, $date)
    {
        return $this->where('student_id', $student_id)
                    ->where('date', $date)
                    ->countAllResults() > 0;
    }

    // Ambil presensi satu siswa berdasarkan tanggal
    public function getByStudentAndDate($student_id, $date)
    {
        return $this->where('student_id', $student_id)
                    ->where('date', $date)
                    ->first();
    }

    // Rekap presensi siswa per bulan
    public function getMonthlySummary($student_id, $month, $year)
    {
        return $this->db->table('student_attendance')
            ->select('status, COUNT(*) as total')
            ->where('student_id', $student_id)
            ->where('MONTH(date)', $month)
            ->where('YEAR(date)', $year)
            ->groupBy('status')
            ->get()->getResultArray();
    }

    // Statistik kehadiran per kelas pada tanggal tertentu
    public function getDailyStats($date, $grade = null)
    {
        $builder = $this->db->table('student_attendance sa')
            ->select('sa.status, COUNT(*) as total')
            ->join('students s', 's.id = sa.student_id')
            ->where('sa.date', $date)
            ->groupBy('sa.status');

        if ($grade) $builder->where('s.grade', $grade);

        return $builder->get()->getResultArray();
    }
}
