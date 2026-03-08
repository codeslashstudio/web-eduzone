<?php

namespace App\Controllers;

class Ujian extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    // Buat & kelola ujian
    private array $editRoles = ['superadmin', 'kurikulum', 'guru_mapel', 'wali_kelas'];
    // Lihat daftar ujian
    private array $viewRoles = ['superadmin', 'kurikulum', 'guru_mapel', 'wali_kelas', 'kepsek', 'tu'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if (!in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('ujian'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    private function getTeacherId(): ?int
    {
        $row = $this->db()->query(
            "SELECT id FROM teachers WHERE user_id = ? LIMIT 1",
            [session()->get('user_id')]
        )->getRowArray();
        return $row ? (int)$row['id'] : null;
    }

    private function canManage(array $exam): bool
    {
        $role = session()->get('role');
        if (in_array($role, ['superadmin', 'kurikulum'])) return true;
        $teacherId = $this->getTeacherId();
        return $exam['supervisor_id'] == $teacherId;
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();
        $db        = $this->db();
        $role      = session()->get('role');
        $teacherId = $this->getTeacherId();

        $search  = $this->request->getGet('search')   ?? '';
        $bulan   = $this->request->getGet('bulan')    ?? date('m');
        $tahun   = $this->request->getGet('tahun')    ?? date('Y');
        $classId = $this->request->getGet('class_id') ?? '';

        $sql = "
            SELECT e.*, t.full_name AS supervisor_name,
                   COUNT(eq.id) AS jumlah_soal
            FROM exams e
            LEFT JOIN teachers t ON t.id = e.supervisor_id
            LEFT JOIN exam_questions eq ON eq.exam_id = e.id
            WHERE MONTH(e.date) = ? AND YEAR(e.date) = ?
        ";
        $params = [(int)$bulan, (int)$tahun];

        // Guru hanya lihat ujian yang dia awasi atau dia buat soalnya
        if (in_array($role, ['guru_mapel', 'wali_kelas']) && $teacherId) {
            $sql .= " AND (e.supervisor_id = ? OR EXISTS (
                SELECT 1 FROM exam_questions eq2 WHERE eq2.exam_id = e.id AND eq2.teacher_id = ?
            ))";
            $params[] = $teacherId;
            $params[] = $teacherId;
        }

        if ($search) {
            $sql .= " AND (e.name LIKE ? OR e.subject LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%";
        }
        if ($classId) {
            $sql .= " AND e.class_id = ?";
            $params[] = $classId;
        }

        $sql .= " GROUP BY e.id ORDER BY e.date DESC";
        $list = $db->query($sql, $params)->getResultArray();

        $stats = $db->query("
            SELECT COUNT(*) total,
                   SUM(CASE WHEN date >= CURDATE() THEN 1 ELSE 0 END) mendatang,
                   SUM(CASE WHEN date < CURDATE()  THEN 1 ELSE 0 END) selesai
            FROM exams
            WHERE MONTH(date) = ? AND YEAR(date) = ?
        ", [(int)$bulan, (int)$tahun])->getRowArray();

        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();

        return view('ujian/index', [
            'title'     => 'Ujian',
            'list'      => $list,
            'stats'     => $stats,
            'kelasList' => $kelasList,
            'search'    => $search,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'classId'   => $classId,
            'canEdit'   => in_array($role, $this->editRoles),
        ]);
    }

    // ==============================
    // DETAIL + daftar soal
    // ==============================
    public function detail($id)
    {
        $this->authCheck();
        $db   = $this->db();
        $exam = $db->query("
            SELECT e.*, t.full_name AS supervisor_name, c.nama_kelas
            FROM exams e
            LEFT JOIN teachers t ON t.id = e.supervisor_id
            LEFT JOIN classes c  ON c.id = e.class_id
            WHERE e.id = ?", [$id])->getRowArray();

        if (!$exam) return redirect()->to(base_url('ujian'))->with('error', 'Ujian tidak ditemukan');

        $soalList = $db->query("
            SELECT eq.*, t.full_name AS pembuat
            FROM exam_questions eq
            LEFT JOIN teachers t ON t.id = eq.teacher_id
            WHERE eq.exam_id = ?
            ORDER BY eq.id ASC", [$id])->getResultArray();

        return view('ujian/detail', [
            'title'     => 'Detail Ujian',
            'exam'      => $exam,
            'soalList'  => $soalList,
            'canEdit'   => $this->canManage($exam),
            'canAddSoal'=> in_array(session()->get('role'), $this->editRoles),
        ]);
    }

    // ==============================
    // ADD UJIAN
    // ==============================
    public function add()
    {
        $this->authCheck(true);
        $db = $this->db();
        $kelasList = $db->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        $guruList  = $db->query("SELECT id, full_name FROM teachers WHERE is_active=1 ORDER BY full_name")->getResultArray();

        return view('ujian/form', [
            'title'     => 'Buat Ujian',
            'mode'      => 'add',
            'exam'      => [],
            'kelasList' => $kelasList,
            'guruList'  => $guruList,
            'teacherId' => $this->getTeacherId(),
        ]);
    }

    // ==============================
    // STORE UJIAN
    // ==============================
    public function store()
    {
        $this->authCheck(true);
        $this->db()->table('exams')->insert([
            'name'          => $this->request->getPost('name'),
            'subject'       => $this->request->getPost('subject'),
            'grade'         => $this->request->getPost('grade'),
            'major'         => $this->request->getPost('major'),
            'class_id'      => $this->request->getPost('class_id') ?: null,
            'date'          => $this->request->getPost('date'),
            'start_time'    => $this->request->getPost('start_time'),
            'end_time'      => $this->request->getPost('end_time'),
            'supervisor_id' => $this->request->getPost('supervisor_id') ?: $this->getTeacherId(),
        ]);
        return redirect()->to(base_url('ujian'))->with('success', 'Ujian berhasil dibuat');
    }

    // ==============================
    // EDIT UJIAN
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);
        $exam = $this->db()->query("SELECT * FROM exams WHERE id = ?", [$id])->getRowArray();
        if (!$exam || !$this->canManage($exam)) {
            return redirect()->to(base_url('ujian'))->with('error', 'Akses ditolak');
        }
        $kelasList = $this->db()->query("SELECT id, nama_kelas FROM classes WHERE is_active=1 ORDER BY nama_kelas")->getResultArray();
        $guruList  = $this->db()->query("SELECT id, full_name FROM teachers WHERE is_active=1 ORDER BY full_name")->getResultArray();

        return view('ujian/form', [
            'title'     => 'Edit Ujian',
            'mode'      => 'edit',
            'exam'      => $exam,
            'kelasList' => $kelasList,
            'guruList'  => $guruList,
            'teacherId' => $this->getTeacherId(),
        ]);
    }

    // ==============================
    // UPDATE UJIAN
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);
        $exam = $this->db()->query("SELECT * FROM exams WHERE id = ?", [$id])->getRowArray();
        if (!$exam || !$this->canManage($exam)) {
            return redirect()->to(base_url('ujian'))->with('error', 'Akses ditolak');
        }
        $this->db()->table('exams')->update([
            'name'          => $this->request->getPost('name'),
            'subject'       => $this->request->getPost('subject'),
            'grade'         => $this->request->getPost('grade'),
            'major'         => $this->request->getPost('major'),
            'class_id'      => $this->request->getPost('class_id') ?: null,
            'date'          => $this->request->getPost('date'),
            'start_time'    => $this->request->getPost('start_time'),
            'end_time'      => $this->request->getPost('end_time'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
        ], ['id' => $id]);
        return redirect()->to(base_url('ujian/' . $id))->with('success', 'Ujian berhasil diperbarui');
    }

    // ==============================
    // DELETE UJIAN
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('ujian'));
        $exam = $this->db()->query("SELECT * FROM exams WHERE id = ?", [$id])->getRowArray();
        if (!$exam || !$this->canManage($exam)) {
            return redirect()->to(base_url('ujian'))->with('error', 'Akses ditolak');
        }
        $this->db()->table('exam_questions')->delete(['exam_id' => $id]);
        $this->db()->table('exams')->delete(['id' => $id]);
        return redirect()->to(base_url('ujian'))->with('success', 'Ujian berhasil dihapus');
    }

    // ==============================
    // ADD SOAL
    // ==============================
    public function addSoal($examId)
    {
        $this->authCheck(true);
        $exam = $this->db()->query("SELECT * FROM exams WHERE id = ?", [$examId])->getRowArray();
        if (!$exam) return redirect()->to(base_url('ujian'))->with('error', 'Ujian tidak ditemukan');

        return view('ujian/soal_form', [
            'title'    => 'Tambah Soal',
            'mode'     => 'add',
            'exam'     => $exam,
            'soal'     => [],
            'teacherId'=> $this->getTeacherId(),
        ]);
    }

    // ==============================
    // STORE SOAL
    // ==============================
    public function storeSoal($examId)
    {
        $this->authCheck(true);
        $teacherId = $this->getTeacherId();

        $this->db()->table('exam_questions')->insert([
            'exam_id'        => $examId,
            'teacher_id'     => $teacherId,
            'question'       => $this->request->getPost('question'),
            'option_a'       => $this->request->getPost('option_a'),
            'option_b'       => $this->request->getPost('option_b'),
            'option_c'       => $this->request->getPost('option_c'),
            'option_d'       => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
        ]);

        // Kalau tambah lagi
        if ($this->request->getPost('action') === 'save_and_add') {
            return redirect()->to(base_url('ujian/' . $examId . '/soal/add'))
                ->with('success', 'Soal disimpan, tambah soal berikutnya');
        }

        return redirect()->to(base_url('ujian/' . $examId))->with('success', 'Soal berhasil ditambahkan');
    }

    // ==============================
    // EDIT SOAL
    // ==============================
    public function editSoal($examId, $soalId)
    {
        $this->authCheck(true);
        $exam = $this->db()->query("SELECT * FROM exams WHERE id = ?", [$examId])->getRowArray();
        $soal = $this->db()->query("SELECT * FROM exam_questions WHERE id = ? AND exam_id = ?", [$soalId, $examId])->getRowArray();
        if (!$exam || !$soal) return redirect()->to(base_url('ujian/' . $examId));

        return view('ujian/soal_form', [
            'title'    => 'Edit Soal',
            'mode'     => 'edit',
            'exam'     => $exam,
            'soal'     => $soal,
            'teacherId'=> $this->getTeacherId(),
        ]);
    }

    // ==============================
    // UPDATE SOAL
    // ==============================
    public function updateSoal($examId, $soalId)
    {
        $this->authCheck(true);
        $this->db()->table('exam_questions')->update([
            'question'       => $this->request->getPost('question'),
            'option_a'       => $this->request->getPost('option_a'),
            'option_b'       => $this->request->getPost('option_b'),
            'option_c'       => $this->request->getPost('option_c'),
            'option_d'       => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
        ], ['id' => $soalId, 'exam_id' => $examId]);
        return redirect()->to(base_url('ujian/' . $examId))->with('success', 'Soal berhasil diperbarui');
    }

    // ==============================
    // DELETE SOAL
    // ==============================
    public function deleteSoal($examId, $soalId)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('ujian/' . $examId));
        $this->db()->table('exam_questions')->delete(['id' => $soalId, 'exam_id' => $examId]);
        return redirect()->to(base_url('ujian/' . $examId))->with('success', 'Soal berhasil dihapus');
    }
}