<?php

namespace App\Controllers;

use App\Models\StudentAttendanceModel;
use CodeIgniter\Controller;

class PresensiSiswa extends Controller
{
    protected $studentAttendanceModel;
    protected $db;

    public function __construct()
    {
        $this->studentAttendanceModel = new StudentAttendanceModel();
        $this->db = \Config\Database::connect();
    }

    // Halaman daftar presensi siswa
    public function index()
    {
        $date       = $this->request->getGet('date') ?? date('Y-m-d');
        $grade      = $this->request->getGet('grade');
        $major_id   = $this->request->getGet('major_id');
        $class_group = $this->request->getGet('class_group');

        $majors = $this->db->table('majors')->where('is_active', 1)->get()->getResultArray();

        $data = [
            'title'       => 'Presensi Siswa',
            'date'        => $date,
            'grade'       => $grade,
            'major_id'    => $major_id,
            'class_group' => $class_group,
            'majors'      => $majors,
            'attendances' => $this->studentAttendanceModel->getAttendanceWithStudent($date, $grade, $major_id, $class_group),
            'stats'       => $this->getStats($date, $grade),
        ];

        return view('presensi/siswa/index', $data);
    }

    // Halaman input presensi massal siswa
    public function input()
    {
        $date        = $this->request->getGet('date') ?? date('Y-m-d');
        $grade       = $this->request->getGet('grade');
        $major_id    = $this->request->getGet('major_id');
        $class_group = $this->request->getGet('class_group');

        $majors = $this->db->table('majors')->where('is_active', 1)->get()->getResultArray();

        // Ambil siswa aktif sesuai filter
        $builder = $this->db->table('students s')
            ->select('s.id, s.full_name, s.nis, s.grade, s.class_group, m.abbreviation as major')
            ->join('majors m', 'm.id = s.major_id', 'left')
            ->where('s.status', 'aktif')
            ->orderBy('s.full_name', 'ASC');

        if ($grade) $builder->where('s.grade', $grade);
        if ($major_id) $builder->where('s.major_id', $major_id);
        if ($class_group) $builder->where('s.class_group', $class_group);

        $students = $builder->get()->getResultArray();

        // Ambil data presensi yang sudah ada
        $existing = $this->studentAttendanceModel->getAttendanceWithStudent($date, $grade, $major_id, $class_group);
        $existingMap = [];
        foreach ($existing as $e) {
            $existingMap[$e['student_id']] = $e;
        }

        $data = [
            'title'       => 'Input Presensi Siswa',
            'date'        => $date,
            'grade'       => $grade,
            'major_id'    => $major_id,
            'class_group' => $class_group,
            'majors'      => $majors,
            'students'    => $students,
            'existingMap' => $existingMap,
        ];

        return view('presensi/siswa/input', $data);
    }

    // Simpan presensi massal siswa
    public function store()
    {
        $date        = $this->request->getPost('date');
        $student_ids = $this->request->getPost('student_id');
        $statuses    = $this->request->getPost('status');
        $notes       = $this->request->getPost('notes');
        $check_ins   = $this->request->getPost('check_in');
        $check_outs  = $this->request->getPost('check_out');

        if (!$student_ids) {
            return redirect()->back()->with('error', 'Tidak ada data siswa yang dikirim.');
        }

        $recorded_by = session()->get('user_id');
        $success = 0;
        $updated = 0;

        foreach ($student_ids as $i => $student_id) {
            $rowData = [
                'student_id'  => $student_id,
                'date'        => $date,
                'status'      => $statuses[$i] ?? 'Hadir',
                'notes'       => $notes[$i] ?? null,
                'check_in'    => !empty($check_ins[$i]) ? $check_ins[$i] : null,
                'check_out'   => !empty($check_outs[$i]) ? $check_outs[$i] : null,
                'recorded_by' => $recorded_by,
            ];

            $existing = $this->studentAttendanceModel->getByStudentAndDate($student_id, $date);

            if ($existing) {
                $this->studentAttendanceModel->update($existing['id'], $rowData);
                $updated++;
            } else {
                $this->studentAttendanceModel->insert($rowData);
                $success++;
            }
        }

        $redirect = '/presensi-siswa?date=' . $date;
        if ($this->request->getPost('grade')) $redirect .= '&grade=' . $this->request->getPost('grade');
        if ($this->request->getPost('major_id')) $redirect .= '&major_id=' . $this->request->getPost('major_id');
        if ($this->request->getPost('class_group')) $redirect .= '&class_group=' . $this->request->getPost('class_group');

        return redirect()->to($redirect)
                         ->with('success', "Presensi disimpan: {$success} baru, {$updated} diperbarui.");
    }

    // Edit presensi satu siswa
    public function edit($id)
    {
        $attendance = $this->studentAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-siswa')->with('error', 'Data tidak ditemukan.');
        }

        $student = $this->db->table('students s')
            ->select('s.*, m.abbreviation as major')
            ->join('majors m', 'm.id = s.major_id', 'left')
            ->where('s.id', $attendance['student_id'])
            ->get()->getRowArray();

        $data = [
            'title'      => 'Edit Presensi Siswa',
            'attendance' => $attendance,
            'student'    => $student,
        ];

        return view('presensi/siswa/edit', $data);
    }

    // Update presensi satu siswa
    public function update($id)
    {
        $attendance = $this->studentAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-siswa')->with('error', 'Data tidak ditemukan.');
        }

        $rowData = [
            'status'    => $this->request->getPost('status'),
            'check_in'  => $this->request->getPost('check_in') ?: null,
            'check_out' => $this->request->getPost('check_out') ?: null,
            'notes'     => $this->request->getPost('notes'),
        ];

        $this->studentAttendanceModel->update($id, $rowData);

        return redirect()->to('/presensi-siswa?date=' . $attendance['date'])
                         ->with('success', 'Presensi berhasil diperbarui.');
    }

    // Hapus presensi
    public function delete($id)
    {
        $attendance = $this->studentAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-siswa')->with('error', 'Data tidak ditemukan.');
        }

        $this->studentAttendanceModel->delete($id);

        return redirect()->to('/presensi-siswa?date=' . $attendance['date'])
                         ->with('success', 'Presensi berhasil dihapus.');
    }

    // Rekap bulanan siswa
    public function rekap()
    {
        $month       = $this->request->getGet('month') ?? date('m');
        $year        = $this->request->getGet('year') ?? date('Y');
        $grade       = $this->request->getGet('grade');
        $major_id    = $this->request->getGet('major_id');
        $class_group = $this->request->getGet('class_group');

        $majors = $this->db->table('majors')->where('is_active', 1)->get()->getResultArray();

        $builder = $this->db->table('students s')
            ->select('s.id, s.full_name, s.nis, s.grade, s.class_group, m.abbreviation as major')
            ->join('majors m', 'm.id = s.major_id', 'left')
            ->where('s.status', 'aktif')
            ->orderBy('s.full_name', 'ASC');

        if ($grade) $builder->where('s.grade', $grade);
        if ($major_id) $builder->where('s.major_id', $major_id);
        if ($class_group) $builder->where('s.class_group', $class_group);

        $students = $builder->get()->getResultArray();

        $rekapData = [];
        foreach ($students as $student) {
            $summary = $this->studentAttendanceModel->getMonthlySummary($student['id'], $month, $year);
            $map = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
            foreach ($summary as $s) {
                $map[$s['status']] = $s['total'];
            }
            $rekapData[] = array_merge($student, $map);
        }

        $data = [
            'title'       => 'Rekap Presensi Siswa',
            'month'       => $month,
            'year'        => $year,
            'grade'       => $grade,
            'major_id'    => $major_id,
            'class_group' => $class_group,
            'majors'      => $majors,
            'rekapData'   => $rekapData,
        ];

        return view('presensi/siswa/rekap', $data);
    }

    // Helper statistik harian
    private function getStats($date, $grade = null)
    {
        $builder = $this->db->table('student_attendance sa')
            ->select('sa.status, COUNT(*) as total')
            ->join('students s', 's.id = sa.student_id')
            ->where('sa.date', $date)
            ->groupBy('sa.status');

        if ($grade) $builder->where('s.grade', $grade);

        $rows = $builder->get()->getResultArray();
        $stats = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        foreach ($rows as $r) {
            $stats[$r['status']] = $r['total'];
        }

        $builderTotal = $this->db->table('students')->where('status', 'aktif');
        if ($grade) $builderTotal->where('grade', $grade);
        $stats['total_siswa'] = $builderTotal->countAllResults();
        $stats['belum_absen'] = $stats['total_siswa'] - array_sum([$stats['Hadir'], $stats['Izin'], $stats['Sakit'], $stats['Alpa']]);

        return $stats;
    }
}
