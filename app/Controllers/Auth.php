<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // Map role slug DB → route dashboard
    private array $roleRoutes = [
        'superadmin' => '/dashboard',
        'kepsek'     => '/dashboard/kepsek',
        'tu'         => '/dashboard/tu',
        'kurikulum'  => '/dashboard/kurikulum',
        'guru_mapel' => '/dashboard/guru',
        'wali_kelas' => '/dashboard/wakel',
        'kesiswaan'  => '/dashboard/kesiswaan',
        'bk'         => '/dashboard/bk',
        'toolman'    => '/dashboard/toolman',
        'siswa'      => '/dashboard/siswa',
    ];

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login');
    }

    public function doLogin()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi');
        }

        // JOIN roles untuk ambil warna & icon
        $db   = \Config\Database::connect();
        $user = $db->table('users u')
                   ->select('u.*, r.name AS role_name, r.color_primary, r.color_secondary, r.icon AS role_icon')
                   ->join('roles r', 'r.id = u.role_id', 'left')
                   ->where('u.username', $username)
                   ->orWhere('u.email', $username) // bisa login pakai email juga
                   ->get()
                   ->getRowArray();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username tidak ditemukan');
        }

        if (!$user['is_active']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password salah');
        }

        // Update last_login
        $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Simpan session lengkap
        session()->set([
            'isLoggedIn'      => true,
            'user_id'         => $user['id'],
            'username'        => $user['username'],
            'email'           => $user['email'],
            'role'            => $user['role'],           // slug: guru_mapel, wali_kelas, dll
            'role_name'       => $user['role_name']       ?? ucfirst($user['role']),
            'role_icon'       => $user['role_icon']       ?? 'fa-user',
            'color_primary'   => $user['color_primary']   ?? '#4c6ef5',
            'color_secondary' => $user['color_secondary'] ?? '#3b5bdb',
        ]);

        return $this->redirectByRole($user['role']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout.');
    }

    // ── Helper ───────────────────────────────────────────────
    private function redirectByRole(string $role)
    {
        $route = $this->roleRoutes[$role] ?? '/dashboard';
        return redirect()->to($route)->with('success', 'Login berhasil! Selamat datang.');
    }
}