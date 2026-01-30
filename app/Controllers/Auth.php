<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }


    public function doLogin()
    {
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar');
        }

        if ((int)$user['is_active'] !== 1) {
            return redirect()->back()->with('error', 'Akun tidak aktif');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        // Simpan session
        session()->set([
            'isLoggedIn' => true,
            'user_id'   => $user['id'],
            'fullname'  => $user['fullname'],
            'username'  => $user['fullname'], // PENTING
            'email'     => $user['email'],
        ]);


        return redirect()->to('/dashboard')->with('success', 'Login berhasil');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        $userModel = new UserModel();

        // Ambil data dari form
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $this->request->getPost('email'),
            'school'   => $this->request->getPost('school'),
            'role_id'     => $this->request->getPost('role'),
            'phone'    => $this->request->getPost('phone'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'is_active' => 1
        ];

        // Validasi email unik
        if ($userModel->where('email', $data['email'])->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar');
        }

        // Simpan ke database
        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login');
    }

    public function logout()
    {
        // Destroy session
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah logout');
    }
}
