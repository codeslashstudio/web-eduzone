<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    private array $roles = [
        'kepsek'     => 'Kepala Sekolah',
        'tu'         => 'Tata Usaha',
        'kurikulum'  => 'Kurikulum',
        'guru_mapel' => 'Guru Mapel',
        'wali_kelas' => 'Wali Kelas',
        'kesiswaan'  => 'Kesiswaan',
        'bk'         => 'BK',
        'toolman'    => 'Toolman',
        'siswa'      => 'Siswa',
        'superadmin' => 'Superadmin',
    ];

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    private function authCheck(): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        if (session()->get('role') !== 'superadmin') {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();

        $search   = $this->request->getGet('search') ?? '';
        $roleFilter = $this->request->getGet('role') ?? '';
        $status   = $this->request->getGet('status') ?? '';

        $builder = $this->userModel->orderBy('created_at', 'DESC');

        if ($search)     $builder->groupStart()->like('username', $search)->orLike('email', $search)->groupEnd();
        if ($roleFilter) $builder->where('role', $roleFilter);
        if ($status !== '') $builder->where('is_active', (int)$status);

        $users = $builder->findAll();

        // Stats
        $db = \Config\Database::connect();
        $stats = [
            'total'   => $this->userModel->countAll(),
            'aktif'   => $this->userModel->where('is_active', 1)->countAllResults(),
            'nonaktif'=> $this->userModel->where('is_active', 0)->countAllResults(),
            'hari_ini'=> $db->query("SELECT COUNT(*) c FROM users WHERE DATE(last_login) = CURDATE()")->getRow()->c,
        ];

        return view('users/index', [
            'title'      => 'Manajemen User',
            'users'      => $users,
            'stats'      => $stats,
            'roles'      => $this->roles,
            'search'     => $search,
            'roleFilter' => $roleFilter,
            'status'     => $status,
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck();
        return view('users/form', [
            'title' => 'Tambah User',
            'mode'  => 'add',
            'user'  => [],
            'roles' => $this->roles,
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck();

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username]",
            'email'    => "required|valid_email|is_unique[users.email]",
            'password' => "required|min_length[6]",
            'role'     => "required|in_list[" . implode(',', array_keys($this->roles)) . "]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->skipValidation(true)->insert([
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('users'))->with('success', 'User berhasil ditambahkan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck();
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to(base_url('users'))->with('error', 'User tidak ditemukan');

        return view('users/form', [
            'title' => 'Edit User',
            'mode'  => 'edit',
            'user'  => $user,
            'roles' => $this->roles,
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck();
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to(base_url('users'))->with('error', 'User tidak ditemukan');

        // Cegah superadmin ubah role/status dirinya sendiri
        $isSelf = ($id == session()->get('user_id'));

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'     => "required|in_list[" . implode(',', array_keys($this->roles)) . "]",
        ];
        $newPass = $this->request->getPost('password');
        if ($newPass) $rules['password'] = 'min_length[6]';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'role'      => $isSelf ? $user['role'] : $this->request->getPost('role'),
            'is_active' => $isSelf ? 1 : ($this->request->getPost('is_active') ? 1 : 0),
        ];
        if ($newPass) $data['password'] = password_hash($newPass, PASSWORD_BCRYPT);

        $this->userModel->skipValidation(true)->update($id, $data);
        return redirect()->to(base_url('users'))->with('success', 'User berhasil diperbarui');
    }

    // ==============================
    // TOGGLE AKTIF
    // ==============================
    public function toggleStatus($id)
    {
        $this->authCheck();
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('users'));

        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to(base_url('users'))->with('error', 'User tidak ditemukan');
        if ($id == session()->get('user_id')) return redirect()->to(base_url('users'))->with('error', 'Tidak dapat menonaktifkan akun sendiri');

        $newStatus = $user['is_active'] ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);
        $msg = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to(base_url('users'))->with('success', "User {$user['username']} berhasil {$msg}");
    }

    // ==============================
    // RESET PASSWORD
    // ==============================
    public function resetPassword($id)
    {
        $this->authCheck();
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('users'));

        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to(base_url('users'))->with('error', 'User tidak ditemukan');

        $newPass = $this->request->getPost('new_password');
        if (!$newPass || strlen($newPass) < 6) {
            return redirect()->to(base_url('users'))->with('error', 'Password minimal 6 karakter');
        }

        $this->userModel->update($id, ['password' => password_hash($newPass, PASSWORD_BCRYPT)]);
        return redirect()->to(base_url('users'))->with('success', "Password {$user['username']} berhasil direset");
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck();
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('users'));
        if ($id == session()->get('user_id')) return redirect()->to(base_url('users'))->with('error', 'Tidak dapat menghapus akun sendiri');

        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to(base_url('users'))->with('error', 'User tidak ditemukan');

        $this->userModel->delete($id);
        return redirect()->to(base_url('users'))->with('success', "User {$user['username']} berhasil dihapus");
    }
}