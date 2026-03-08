<?php

namespace App\Controllers;

class Account extends BaseController
{
    public function password()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('account/password');
    }

    public function pengaturan()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        return redirect()->to('/dashboard')->with('error', 'Halaman pengaturan belum tersedia');
    }

    public function updatePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/password')->with('error', 'Method tidak diizinkan');
        }

        $current = $this->request->getPost('current_password');
        $new     = $this->request->getPost('new_password');
        $confirm = $this->request->getPost('confirm_password');

        if (empty($current) || empty($new) || empty($confirm)) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi');
        }

        if (strlen($new) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password baru minimal 6 karakter');
        }

        if ($new !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok');
        }

        $db   = \Config\Database::connect();
        $user = $db->table('users')->where('id', session()->get('user_id'))->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Akun tidak ditemukan');
        }

        if (!password_verify($current, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password saat ini salah');
        }

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $db->table('users')->where('id', $user['id'])->update([
            'password'   => $hashed,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dashboard')->with('success', 'Password berhasil diperbarui');
    }
}
