<?php

namespace App\Controllers;

use App\Models\UserModel;

class Pengumuman extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    // Role yang boleh buat/edit/hapus pengumuman
    private array $editRoles = ['superadmin', 'kepsek', 'tu', 'kesiswaan', 'kurikulum'];

    private function canEdit(): bool
    {
        return in_array(session()->get('role'), $this->editRoles);
    }

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
    }

    // ==============================
    // INDEX — daftar pengumuman
    // ==============================
    public function index()
    {
        $this->authCheck();
        $role = session()->get('role');
        $db   = $this->db();

        // Siswa & guru hanya lihat pengumuman yang ditujukan ke role mereka atau 'all'
        $sql = "
            SELECT p.*, u.username AS created_by_name
            FROM announcements p
            LEFT JOIN users u ON u.id = p.created_by
            WHERE p.published_at <= CURDATE()
        ";

        // Filter visibility kecuali superadmin/kepsek/tu
        if (!in_array($role, ['superadmin', 'kepsek', 'tu'])) {
            $sql .= " AND (
                p.visibility IS NULL
                OR p.visibility = ''
                OR FIND_IN_SET(?, p.visibility)
            )";
            $params = [$role];
        } else {
            $params = [];
        }

        $sql .= " ORDER BY p.is_important DESC, p.published_at DESC";
        $list = $db->query($sql, $params)->getResultArray();

        return view('pengumuman/index', [
            'title'    => 'Pengumuman',
            'list'     => $list,
            'canEdit'  => $this->canEdit(),
        ]);
    }

    // ==============================
    // DETAIL
    // ==============================
    public function detail($id)
    {
        $this->authCheck();
        $row = $this->db()->query(
            "SELECT p.*, u.username AS created_by_name
             FROM announcements p LEFT JOIN users u ON u.id = p.created_by
             WHERE p.id = ?", [$id]
        )->getRowArray();

        if (!$row) return redirect()->to(base_url('pengumuman'))->with('error', 'Pengumuman tidak ditemukan');

        return view('pengumuman/detail', [
            'title'   => $row['title'],
            'item'    => $row,
            'canEdit' => $this->canEdit(),
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck();
        if (!$this->canEdit()) return redirect()->to(base_url('pengumuman'))->with('error', 'Akses ditolak');
        return view('pengumuman/form', ['title' => 'Buat Pengumuman', 'mode' => 'add', 'item' => []]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck();
        if (!$this->canEdit()) return redirect()->to(base_url('pengumuman'))->with('error', 'Akses ditolak');

        $visibility = $this->request->getPost('visibility') ?? [];
        $visStr     = is_array($visibility) ? implode(',', $visibility) : $visibility;

        $this->db()->table('announcements')->insert([
            'title'        => $this->request->getPost('title'),
            'content'      => $this->request->getPost('content'),
            'visibility'   => $visStr ?: null,
            'is_important' => $this->request->getPost('is_important') ? 1 : 0,
            'published_at' => $this->request->getPost('published_at') ?: date('Y-m-d'),
            'created_by'   => session()->get('user_id'),
        ]);

        return redirect()->to(base_url('pengumuman'))->with('success', 'Pengumuman berhasil dibuat');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck();
        if (!$this->canEdit()) return redirect()->to(base_url('pengumuman'))->with('error', 'Akses ditolak');

        $row = $this->db()->query("SELECT * FROM announcements WHERE id = ?", [$id])->getRowArray();
        if (!$row) return redirect()->to(base_url('pengumuman'))->with('error', 'Tidak ditemukan');

        return view('pengumuman/form', ['title' => 'Edit Pengumuman', 'mode' => 'edit', 'item' => $row]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck();
        if (!$this->canEdit()) return redirect()->to(base_url('pengumuman'))->with('error', 'Akses ditolak');

        $visibility = $this->request->getPost('visibility') ?? [];
        $visStr     = is_array($visibility) ? implode(',', $visibility) : $visibility;

        $this->db()->table('announcements')->update([
            'title'        => $this->request->getPost('title'),
            'content'      => $this->request->getPost('content'),
            'visibility'   => $visStr ?: null,
            'is_important' => $this->request->getPost('is_important') ? 1 : 0,
            'published_at' => $this->request->getPost('published_at') ?: date('Y-m-d'),
        ], ['id' => $id]);

        return redirect()->to(base_url('pengumuman'))->with('success', 'Pengumuman berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck();
        if (!$this->canEdit()) return redirect()->to(base_url('pengumuman'))->with('error', 'Akses ditolak');
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('pengumuman'));

        $this->db()->table('announcements')->delete(['id' => $id]);
        return redirect()->to(base_url('pengumuman'))->with('success', 'Pengumuman berhasil dihapus');
    }
}