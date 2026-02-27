<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
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

        // Cari user + JOIN roles untuk ambil warna & icon sekaligus
        $db   = \Config\Database::connect();
        $user = $db->table('users u')
                   ->select('u.*, r.name AS role_name, r.color_primary, r.color_secondary, r.icon AS role_icon')
                   ->join('roles r', 'r.id = u.role_id', 'left')
                   ->where('u.username', $username)
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

        // ============================================================
        // Simpan session
        // - role          → untuk cek akses di controller (slug)
        // - role_name     → untuk tampilan UI "Kepala Sekolah"
        // - role_icon     → untuk icon sidebar "fa-user-tie"
        // - color_primary & color_secondary → untuk CSS variables
        //   di layout/main.php, tidak perlu dicek lagi di view manapun
        // ============================================================
        session()->set([
            'isLoggedIn'      => true,
            'user_id'         => $user['id'],
            'username'        => $user['username'],
            'email'           => $user['email'],
            'role'            => $user['role'],
            'role_name'       => $user['role_name']       ?? $user['role'],
            'role_icon'       => $user['role_icon']       ?? 'fa-user',
            'color_primary'   => $user['color_primary']   ?? '#3B82F6',
            'color_secondary' => $user['color_secondary'] ?? '#2563EB',
        ]);

        return redirect()->to('/dashboard')->with('success', 'Login berhasil! Selamat datang.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout.');
    }
}