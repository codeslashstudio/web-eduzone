<?php

namespace App\Controllers;

use App\Models\TeacherAttendanceModel;
use CodeIgniter\Controller;

class PresensiGuru extends Controller
{
    protected $teacherAttendanceModel;
    protected $db;

    public function __construct()
    {
        $this->teacherAttendanceModel = new TeacherAttendanceModel();
        $this->db = \Config\Database::connect();
    }

    // Halaman daftar presensi guru
    public function index()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = [
            'title'       => 'Presensi Guru',
            'date'        => $date,
            'attendances' => $this->teacherAttendanceModel->getAttendanceWithTeacher($date),
            'stats'       => $this->getStats($date),
        ];

        return view('presensi/guru/index', $data);
    }

    // Halaman input presensi massal (semua guru dalam satu form)
    public function input()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        // Ambil semua guru aktif
        $teachers = $this->db->table('teachers')
            ->select('id, full_name, nip')
            ->where('is_active', 1)
            ->orderBy('full_name', 'ASC')
            ->get()->getResultArray();

        // Ambil data presensi yang sudah ada untuk tanggal ini
        $existing = $this->teacherAttendanceModel->getAttendanceWithTeacher($date);
        $existingMap = [];
        foreach ($existing as $e) {
            $existingMap[$e['teacher_id']] = $e;
        }

        $data = [
            'title'       => 'Input Presensi Guru',
            'date'        => $date,
            'teachers'    => $teachers,
            'existingMap' => $existingMap,
        ];

        return view('presensi/guru/input', $data);
    }

    // Simpan presensi massal
    public function store()
    {
        $date        = $this->request->getPost('date');
        $teacher_ids = $this->request->getPost('teacher_id');
        $statuses    = $this->request->getPost('status');
        $notes       = $this->request->getPost('notes');
        $check_ins   = $this->request->getPost('check_in');
        $check_outs  = $this->request->getPost('check_out');

        if (!$teacher_ids) {
            return redirect()->back()->with('error', 'Tidak ada data guru yang dikirim.');
        }

        $recorded_by = session()->get('user_id');
        $success = 0;
        $updated = 0;

        foreach ($teacher_ids as $i => $teacher_id) {
            $rowData = [
                'teacher_id'  => $teacher_id,
                'date'        => $date,
                'status'      => $statuses[$i] ?? 'Hadir',
                'notes'       => $notes[$i] ?? null,
                'check_in'    => !empty($check_ins[$i]) ? $check_ins[$i] : null,
                'check_out'   => !empty($check_outs[$i]) ? $check_outs[$i] : null,
                'recorded_by' => $recorded_by,
            ];

            $existing = $this->teacherAttendanceModel->getByTeacherAndDate($teacher_id, $date);

            if ($existing) {
                $this->teacherAttendanceModel->update($existing['id'], $rowData);
                $updated++;
            } else {
                $this->teacherAttendanceModel->insert($rowData);
                $success++;
            }
        }

        return redirect()->to('/presensi-guru?date=' . $date)
                         ->with('success', "Presensi disimpan: {$success} baru, {$updated} diperbarui.");
    }

    // Edit presensi satu guru
    public function edit($id)
    {
        $attendance = $this->teacherAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-guru')->with('error', 'Data tidak ditemukan.');
        }

        $teacher = $this->db->table('teachers')
            ->where('id', $attendance['teacher_id'])
            ->get()->getRowArray();

        $data = [
            'title'      => 'Edit Presensi Guru',
            'attendance' => $attendance,
            'teacher'    => $teacher,
        ];

        return view('presensi/guru/edit', $data);
    }

    // Update presensi satu guru
    public function update($id)
    {
        $attendance = $this->teacherAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-guru')->with('error', 'Data tidak ditemukan.');
        }

        $rowData = [
            'status'    => $this->request->getPost('status'),
            'check_in'  => $this->request->getPost('check_in') ?: null,
            'check_out' => $this->request->getPost('check_out') ?: null,
            'notes'     => $this->request->getPost('notes'),
        ];

        $this->teacherAttendanceModel->update($id, $rowData);

        return redirect()->to('/presensi-guru?date=' . $attendance['date'])
                         ->with('success', 'Presensi berhasil diperbarui.');
    }

    // Hapus presensi
    public function delete($id)
    {
        $attendance = $this->teacherAttendanceModel->find($id);
        if (!$attendance) {
            return redirect()->to('/presensi-guru')->with('error', 'Data tidak ditemukan.');
        }

        $this->teacherAttendanceModel->delete($id);

        return redirect()->to('/presensi-guru?date=' . $attendance['date'])
                         ->with('success', 'Presensi berhasil dihapus.');
    }

    // Rekap bulanan guru
    public function rekap()
    {
        $month = $this->request->getGet('month') ?? date('m');
        $year  = $this->request->getGet('year') ?? date('Y');

        $teachers = $this->db->table('teachers t')
            ->select('t.id, t.full_name, t.nip')
            ->where('t.is_active', 1)
            ->orderBy('t.full_name', 'ASC')
            ->get()->getResultArray();

        $rekapData = [];
        foreach ($teachers as $teacher) {
            $summary = $this->teacherAttendanceModel->getMonthlySummary($teacher['id'], $month, $year);
            $map = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
            foreach ($summary as $s) {
                $map[$s['status']] = $s['total'];
            }
            $rekapData[] = array_merge($teacher, $map);
        }

        $data = [
            'title'     => 'Rekap Presensi Guru',
            'month'     => $month,
            'year'      => $year,
            'rekapData' => $rekapData,
        ];

        return view('presensi/guru/rekap', $data);
    }

    // Helper statistik harian
    private function getStats($date)
    {
        $rows = $this->db->table('teacher_attendance')
            ->select('status, COUNT(*) as total')
            ->where('date', $date)
            ->groupBy('status')
            ->get()->getResultArray();

        $stats = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        foreach ($rows as $r) {
            $stats[$r['status']] = $r['total'];
        }

        // Total guru aktif
        $stats['total_guru'] = $this->db->table('teachers')->where('is_active', 1)->countAllResults();
        $stats['belum_absen'] = $stats['total_guru'] - array_sum([$stats['Hadir'], $stats['Izin'], $stats['Sakit'], $stats['Alpa']]);

        return $stats;
    }
}
