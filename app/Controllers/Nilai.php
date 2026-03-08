<?php

namespace App\Controllers;

class Nilai extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $inputRoles    = ['superadmin', 'guru_mapel', 'wali_kelas', 'kurikulum'];
    private array $finalizeRoles = ['superadmin', 'kurikulum'];
    private array $viewRoles     = ['superadmin', 'guru_mapel', 'wali_kelas', 'kurikulum', 'kepsek', 'siswa'];

    private function authCheck(string $level = 'view'): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        $allowed = match($level) {
            'finalize' => $this->finalizeRoles,
            'input'    => $this->inputRoles,
            default    => $this->viewRoles,
        };
        if (!in_array($role, $allowed)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getTeacherId(): ?int
    {
        $row = $this->db()->query("SELECT id FROM teachers WHERE user_id=? LIMIT 1", [session()->get('user_id')])->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    private function getStudentId(): ?int
    {
        $row = $this->db()->query("SELECT id FROM students WHERE user_id=? LIMIT 1", [session()->get('user_id')])->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    private function hitungNilaiAkhir(?float $nh, ?float $nt): ?float
    {
        if ($nh === null && $nt === null) return null;
        $nh = $nh ?? 0;
        $nt = $nt ?? 0;
        return round($nh * 0.4 + $nt * 0.6, 2);
    }

    private function hitungPredikat(float $nilai, string $kurikulum, int $kkm = 75): string
    {
        if ($kurikulum === 'Merdeka') {
            if ($nilai >= 90) return 'A';
            if ($nilai >= 80) return 'B';
            if ($nilai >= 70) return 'C';
            return 'D';
        }
        // K13
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= $kkm) return 'C';
        return 'D';
    }

    // ============================
    // INDEX — pilih kelas & semester
    // ============================
    public function index()
    {
        $this->authCheck();
        $db   = $this->db();
        $role = session()->get('role');

        // Siswa langsung ke rapor
        if ($role === 'siswa') {
            return redirect()->to(base_url('nilai/rapor'));
        }

        $tahun    = $this->request->getGet('tahun')    ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';
        $classId  = $this->request->getGet('class_id') ?? '';

        // Guru hanya lihat kelas yang diajarnya
        $teacherId = $this->getTeacherId();
        $sql = "
            SELECT c.*, m.name AS major_name, m.abbreviation,
                   gc.kurikulum, gc.kkm, gc.is_finalized,
                   COUNT(DISTINCT s.id) jumlah_siswa,
                   COUNT(DISTINCT sg.student_id) sudah_dinilai,
                   t.full_name AS wakel_name
            FROM classes c
            LEFT JOIN majors m ON m.id = c.major_id
            LEFT JOIN grade_configs gc ON gc.class_id = c.id
                AND gc.academic_year = ? AND gc.semester = ?
            LEFT JOIN students s ON s.class_id = c.id AND s.status = 'aktif'
            LEFT JOIN student_grades sg ON sg.class_id = c.id
                AND sg.academic_year = ? AND sg.semester = ?
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.academic_year = c.academic_year
            LEFT JOIN teachers t ON t.id = ha.teacher_id
            WHERE c.academic_year = ? AND c.is_active = 1
        ";
        $params = [$tahun, $semester, $tahun, $semester, $tahun];

        if (in_array($role, ['guru_mapel']) && $teacherId) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM schedules sc
                WHERE sc.teacher_id = ? AND sc.class_id = c.id AND sc.is_active = 1
            )";
            $params[] = $teacherId;
        }
        if ($role === 'wali_kelas' && $teacherId) {
            $sql .= " AND ha.teacher_id = ?";
            $params[] = $teacherId;
        }

        $sql .= " GROUP BY c.id ORDER BY c.grade, m.name, c.class_group";
        $kelasList = $db->query($sql, $params)->getResultArray();

        return view('nilai/index', [
            'title'     => 'Manajemen Nilai',
            'kelasList' => $kelasList,
            'tahun'     => $tahun,
            'semester'  => $semester,
            'classId'   => $classId,
            'canInput'  => in_array($role, $this->inputRoles),
            'canFinalize' => in_array($role, $this->finalizeRoles),
        ]);
    }

    // ============================
    // DAFTAR NILAI KELAS
    // ============================
    public function kelas($classId)
    {
        $this->authCheck('view');
        $db       = $this->db();
        $role     = session()->get('role');
        $tahun    = $this->request->getGet('tahun')    ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        $kelas = $db->query("
            SELECT c.*, m.name AS major_name, gc.kurikulum, gc.kkm, gc.is_finalized
            FROM classes c
            LEFT JOIN majors m ON m.id = c.major_id
            LEFT JOIN grade_configs gc ON gc.class_id = c.id
                AND gc.academic_year = ? AND gc.semester = ?
            WHERE c.id = ?", [$tahun, $semester, $classId])->getRowArray();

        if (!$kelas) return redirect()->to(base_url('nilai'))->with('error', 'Kelas tidak ditemukan');

        // Daftar mapel di kelas ini
        $mapelList = $db->query("
            SELECT DISTINCT sc.subject, t.id AS teacher_id, t.full_name AS teacher_name
            FROM schedules sc
            LEFT JOIN teachers t ON t.id = sc.teacher_id
            WHERE sc.class_id = ? AND sc.is_active = 1
            ORDER BY sc.subject", [$classId])->getResultArray();

        // Daftar siswa + nilai per mapel
        $siswaList = $db->query("
            SELECT s.id, s.nis, s.full_name, s.gender,
                   ss.sikap_spiritual, ss.sikap_sosial
            FROM students s
            LEFT JOIN student_sikap ss ON ss.student_id = s.id
                AND ss.academic_year = ? AND ss.semester = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            ORDER BY s.full_name", [$tahun, $semester, $classId])->getResultArray();

        // Ambil semua nilai untuk kelas ini
        $gradesRaw = $db->query("
            SELECT sg.student_id, sg.subject, sg.nilai_harian, sg.nilai_tugas,
                   sg.nilai_akhir, sg.predikat, sg.catatan
            FROM student_grades sg
            WHERE sg.class_id = ? AND sg.academic_year = ? AND sg.semester = ?",
            [$classId, $tahun, $semester])->getResultArray();

        // Index nilai: [student_id][subject]
        $grades = [];
        foreach ($gradesRaw as $g) {
            $grades[$g['student_id']][$g['subject']] = $g;
        }

        return view('nilai/kelas', [
            'title'      => 'Nilai Kelas ' . $kelas['nama_kelas'],
            'kelas'      => $kelas,
            'mapelList'  => $mapelList,
            'siswaList'  => $siswaList,
            'grades'     => $grades,
            'tahun'      => $tahun,
            'semester'   => $semester,
            'canInput'   => in_array($role, $this->inputRoles) && !$kelas['is_finalized'],
            'canFinalize'=> in_array($role, $this->finalizeRoles),
            'canSikap'   => in_array($role, ['superadmin', 'wali_kelas']),
        ]);
    }

    // ============================
    // INPUT NILAI MAPEL (bulk per mapel)
    // ============================
    public function inputMapel($classId)
    {
        $this->authCheck('input');
        $db        = $this->db();
        $role      = session()->get('role');
        $tahun     = $this->request->getGet('tahun')    ?? '2025/2026';
        $semester  = $this->request->getGet('semester') ?? 'Ganjil';
        $subject   = $this->request->getGet('subject')  ?? '';
        $teacherId = $this->getTeacherId();

        $kelas = $db->query("
            SELECT c.*, m.name AS major_name, gc.kurikulum, gc.kkm, gc.is_finalized
            FROM classes c
            LEFT JOIN majors m ON m.id = c.major_id
            LEFT JOIN grade_configs gc ON gc.class_id = c.id
                AND gc.academic_year = ? AND gc.semester = ?
            WHERE c.id = ?", [$tahun, $semester, $classId])->getRowArray();

        if (!$kelas || $kelas['is_finalized']) {
            return redirect()->to(base_url('nilai/kelas/' . $classId))->with('error', 'Nilai sudah dikunci');
        }

        $mapelList = $db->query("
            SELECT DISTINCT sc.subject FROM schedules sc
            WHERE sc.class_id = ? AND sc.is_active = 1
            ORDER BY sc.subject", [$classId])->getResultArray();

        $siswaList = $db->query("
            SELECT s.id, s.nis, s.full_name,
                   sg.nilai_harian, sg.nilai_tugas, sg.nilai_akhir, sg.predikat, sg.catatan
            FROM students s
            LEFT JOIN student_grades sg ON sg.student_id = s.id
                AND sg.subject = ? AND sg.academic_year = ? AND sg.semester = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            ORDER BY s.full_name",
            [$subject, $tahun, $semester, $classId])->getResultArray();

        return view('nilai/input_mapel', [
            'title'     => 'Input Nilai ' . $subject,
            'kelas'     => $kelas,
            'mapelList' => $mapelList,
            'siswaList' => $siswaList,
            'subject'   => $subject,
            'tahun'     => $tahun,
            'semester'  => $semester,
        ]);
    }

    // ============================
    // STORE NILAI MAPEL (bulk)
    // ============================
    public function storeMapel($classId)
    {
        $this->authCheck('input');
        $db        = $this->db();
        $tahun     = $this->request->getPost('tahun')    ?? '2025/2026';
        $semester  = $this->request->getPost('semester') ?? 'Ganjil';
        $subject   = $this->request->getPost('subject')  ?? '';
        $teacherId = $this->getTeacherId();

        $kelas = $db->query("
            SELECT gc.kurikulum, gc.kkm, gc.is_finalized FROM grade_configs gc
            WHERE gc.class_id = ? AND gc.academic_year = ? AND gc.semester = ?",
            [$classId, $tahun, $semester])->getRowArray();

        if ($kelas && $kelas['is_finalized']) {
            return redirect()->back()->with('error', 'Nilai sudah dikunci');
        }

        $kurikulum = $kelas['kurikulum'] ?? 'Merdeka';
        $kkm       = (int)($kelas['kkm'] ?? 75);

        $siswaIds = $this->request->getPost('student_id') ?? [];
        $nhs      = $this->request->getPost('nilai_harian') ?? [];
        $nts      = $this->request->getPost('nilai_tugas')  ?? [];
        $catatans = $this->request->getPost('catatan')       ?? [];

        foreach ($siswaIds as $i => $sid) {
            $nh = isset($nhs[$i]) && $nhs[$i] !== '' ? (float)$nhs[$i] : null;
            $nt = isset($nts[$i]) && $nts[$i] !== '' ? (float)$nts[$i] : null;
            $na = $this->hitungNilaiAkhir($nh, $nt);
            $predikat = $na !== null ? $this->hitungPredikat($na, $kurikulum, $kkm) : null;

            $data = [
                'student_id'   => $sid,
                'class_id'     => $classId,
                'teacher_id'   => $teacherId,
                'subject'      => $subject,
                'academic_year'=> $tahun,
                'semester'     => $semester,
                'nilai_harian' => $nh,
                'nilai_tugas'  => $nt,
                'nilai_akhir'  => $na,
                'predikat'     => $predikat,
                'catatan'      => $catatans[$i] ?? null,
            ];

            // Upsert
            $exists = $db->query("
                SELECT id FROM student_grades
                WHERE student_id=? AND subject=? AND academic_year=? AND semester=?",
                [$sid, $subject, $tahun, $semester])->getRowArray();

            if ($exists) {
                $db->table('student_grades')->update($data, ['id' => $exists['id']]);
            } else {
                $db->table('student_grades')->insert($data);
            }
        }

        return redirect()->to(base_url('nilai/kelas/' . $classId . '?tahun=' . $tahun . '&semester=' . $semester))
            ->with('success', 'Nilai ' . $subject . ' berhasil disimpan');
    }

    // ============================
    // INPUT SIKAP (wali kelas)
    // ============================
    public function inputSikap($classId)
    {
        $this->authCheck('input');
        $db       = $this->db();
        $tahun    = $this->request->getGet('tahun')    ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        $kelas = $db->query("SELECT c.*, m.name AS major_name FROM classes c
            LEFT JOIN majors m ON m.id = c.major_id WHERE c.id = ?", [$classId])->getRowArray();

        $siswaList = $db->query("
            SELECT s.id, s.nis, s.full_name,
                   ss.id AS sikap_id, ss.sikap_spiritual, ss.sikap_sosial,
                   ss.catatan_sikap, ss.catatan_wakel, ss.ekskul,
                   ss.ketidakhadiran_sakit, ss.ketidakhadiran_izin, ss.ketidakhadiran_alpa
            FROM students s
            LEFT JOIN student_sikap ss ON ss.student_id = s.id
                AND ss.academic_year = ? AND ss.semester = ?
            WHERE s.class_id = ? AND s.status = 'aktif'
            ORDER BY s.full_name", [$tahun, $semester, $classId])->getResultArray();

        return view('nilai/input_sikap', [
            'title'    => 'Input Sikap & Kehadiran',
            'kelas'    => $kelas,
            'siswaList'=> $siswaList,
            'tahun'    => $tahun,
            'semester' => $semester,
        ]);
    }

    // ============================
    // STORE SIKAP
    // ============================
    public function storeSikap($classId)
    {
        $this->authCheck('input');
        $db       = $this->db();
        $tahun    = $this->request->getPost('tahun')    ?? '2025/2026';
        $semester = $this->request->getPost('semester') ?? 'Ganjil';

        $siswaIds = $this->request->getPost('student_id') ?? [];
        foreach ($siswaIds as $i => $sid) {
            $data = [
                'student_id'              => $sid,
                'class_id'                => $classId,
                'academic_year'           => $tahun,
                'semester'                => $semester,
                'sikap_spiritual'         => $this->request->getPost('sikap_spiritual')[$i] ?? 'B',
                'sikap_sosial'            => $this->request->getPost('sikap_sosial')[$i]    ?? 'B',
                'catatan_sikap'           => $this->request->getPost('catatan_sikap')[$i]   ?? null,
                'catatan_wakel'           => $this->request->getPost('catatan_wakel')[$i]   ?? null,
                'ketidakhadiran_sakit'    => $this->request->getPost('sakit')[$i]            ?? 0,
                'ketidakhadiran_izin'     => $this->request->getPost('izin')[$i]             ?? 0,
                'ketidakhadiran_alpa'     => $this->request->getPost('alpa')[$i]             ?? 0,
            ];

            $exists = $db->query("SELECT id FROM student_sikap
                WHERE student_id=? AND academic_year=? AND semester=?",
                [$sid, $tahun, $semester])->getRowArray();

            if ($exists) {
                $db->table('student_sikap')->update($data, ['id' => $exists['id']]);
            } else {
                $db->table('student_sikap')->insert($data);
            }
        }

        return redirect()->to(base_url('nilai/kelas/' . $classId . '?tahun=' . $tahun . '&semester=' . $semester))
            ->with('success', 'Sikap & kehadiran berhasil disimpan');
    }

    // ============================
    // FINALIZE (kunci nilai)
    // ============================
    public function finalize($classId)
    {
        $this->authCheck('finalize');
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('nilai'));
        $db       = $this->db();
        $tahun    = $this->request->getPost('tahun')    ?? '2025/2026';
        $semester = $this->request->getPost('semester') ?? 'Ganjil';

        $exists = $db->query("SELECT id FROM grade_configs
            WHERE class_id=? AND academic_year=? AND semester=?",
            [$classId, $tahun, $semester])->getRowArray();

        $data = [
            'is_finalized' => 1,
            'finalized_by' => session()->get('user_id'),
            'finalized_at' => date('Y-m-d H:i:s'),
        ];

        if ($exists) {
            $db->table('grade_configs')->update($data, ['id' => $exists['id']]);
        } else {
            $db->table('grade_configs')->insert(array_merge($data, [
                'class_id'     => $classId,
                'academic_year'=> $tahun,
                'semester'     => $semester,
                'kurikulum'    => 'Merdeka',
            ]));
        }

        return redirect()->to(base_url('nilai/kelas/' . $classId . '?tahun=' . $tahun . '&semester=' . $semester))
            ->with('success', 'Nilai berhasil dikunci — rapor siap dicetak');
    }

    // ============================
    // RAPOR SISWA
    // ============================
    public function rapor($studentId = null)
    {
        $this->authCheck();
        $db   = $this->db();
        $role = session()->get('role');

        // Siswa hanya bisa lihat rapor sendiri
        if ($role === 'siswa') {
            $studentId = $this->getStudentId();
        }

        if (!$studentId) return redirect()->to(base_url('nilai'))->with('error', 'Siswa tidak ditemukan');

        $tahun    = $this->request->getGet('tahun')    ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        $siswa = $db->query("
            SELECT s.*, m.name AS major_name, c.nama_kelas, c.grade AS kelas_grade,
                   t.full_name AS wakel_name
            FROM students s
            LEFT JOIN majors m ON m.id = s.major_id
            LEFT JOIN classes c ON c.id = s.class_id
            LEFT JOIN homeroom_assignments ha ON ha.class_id = c.id AND ha.academic_year = ?
            LEFT JOIN teachers t ON t.id = ha.teacher_id
            WHERE s.id = ?", [$tahun, $studentId])->getRowArray();

        if (!$siswa) return redirect()->to(base_url('nilai'))->with('error', 'Siswa tidak ditemukan');

        $grades = $db->query("
            SELECT sg.*, t.full_name AS teacher_name
            FROM student_grades sg
            LEFT JOIN teachers t ON t.id = sg.teacher_id
            WHERE sg.student_id = ? AND sg.academic_year = ? AND sg.semester = ?
            ORDER BY sg.subject", [$studentId, $tahun, $semester])->getResultArray();

        $sikap = $db->query("
            SELECT * FROM student_sikap
            WHERE student_id = ? AND academic_year = ? AND semester = ?",
            [$studentId, $tahun, $semester])->getRowArray();

        $config = $db->query("
            SELECT * FROM grade_configs
            WHERE class_id = ? AND academic_year = ? AND semester = ?",
            [$siswa['class_id'], $tahun, $semester])->getRowArray();

        // Hitung rata-rata nilai akhir
        $nilaiAkhirList = array_filter(array_column($grades, 'nilai_akhir'));
        $rataRata = count($nilaiAkhirList) > 0 ? round(array_sum($nilaiAkhirList) / count($nilaiAkhirList), 2) : null;

        return view('nilai/rapor', [
            'title'    => 'Rapor ' . $siswa['full_name'],
            'siswa'    => $siswa,
            'grades'   => $grades,
            'sikap'    => $sikap ?? [],
            'config'   => $config ?? [],
            'rataRata' => $rataRata,
            'tahun'    => $tahun,
            'semester' => $semester,
            'canInput' => in_array($role, $this->inputRoles),
        ]);
    }
}