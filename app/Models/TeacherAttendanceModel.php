<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherAttendanceModel extends Model
{
    protected $table            = 'teacher_attendance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'teacher_id', 'date', 'check_in', 'check_out',
        'status', 'notes', 'recorded_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'teacher_id' => 'required|integer',
        'date'       => 'required|valid_date',
        'status'     => 'required|in_list[Hadir,Izin,Sakit,Alpa]',
    ];

    // Ambil semua presensi guru dengan join ke tabel teachers
    public function getAttendanceWithTeacher($date = null)
    {
        $builder = $this->db->table('teacher_attendance ta')
            ->select('ta.*, t.full_name, t.nip')
            ->join('teachers t', 't.id = ta.teacher_id')
            ->orderBy('t.full_name', 'ASC');

        if ($date) {
            $builder->where('ta.date', $date);
        }

        return $builder->get()->getResultArray();
    }

    // Cek apakah guru sudah absen hari ini
    public function isAlreadyRecorded($teacher_id, $date)
    {
        return $this->where('teacher_id', $teacher_id)
                    ->where('date', $date)
                    ->countAllResults() > 0;
    }

    // Ambil presensi satu guru berdasarkan tanggal
    public function getByTeacherAndDate($teacher_id, $date)
    {
        return $this->where('teacher_id', $teacher_id)
                    ->where('date', $date)
                    ->first();
    }

    // Rekap presensi guru per bulan
    public function getMonthlySummary($teacher_id, $month, $year)
    {
        return $this->db->table('teacher_attendance')
            ->select('status, COUNT(*) as total')
            ->where('teacher_id', $teacher_id)
            ->where('MONTH(date)', $month)
            ->where('YEAR(date)', $year)
            ->groupBy('status')
            ->get()->getResultArray();
    }

    // Ambil daftar guru yang belum absen pada tanggal tertentu
    public function getAbsentTeachers($date)
    {
        return $this->db->table('teachers t')
            ->select('t.id, t.full_name, t.nip')
            ->where('t.is_active', 1)
            ->whereNotIn('t.id', function($builder) use ($date) {
                $builder->select('teacher_id')
                        ->from('teacher_attendance')
                        ->where('date', $date);
            })
            ->get()->getResultArray();
    }
}
