<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $data = [
            'username' => session()->get('fullname'),
            'email' => session()->get('email'),
            'role' => session()->get('role')
        ];

        return view('dashboard/index', $data);
    }

    public function selectRole($role = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Valid roles
        $validRoles = ['kepsek', 'tu', 'wakel', 'bk', 'kurikulum', 'guru'];

        if ($role && in_array($role, $validRoles)) {
            // Set selected role in session
            session()->set('selectedRole', $role);

            // Redirect to role-specific dashboard
            return redirect()->to('/dashboard/' . $role);
        }

        return redirect()->to('/dashboard');
    }

    private function getUserData()
    {
        return [
            'username' => session()->get('fullname'),
            'email'    => session()->get('email'),
            'role'     => session()->get('role'),
        ];
    }


    // Role-specific dashboard methods
    public function kepsek()
    {
        $this->checkRole('kepsek');
        return view('Kepsek/index', $this->getUserData());
    }


    public function tu()
    {
        $this->checkRole('tu');
        return view('Tu/index', $this->getUserData());
    }

    public function wakel()
    {
        $this->checkRole('wakel');
        return view('dashboard/wakel', $this->getUserData());
    }

    public function bk()
    {
        $this->checkRole('bk');
        return view('dashboard/bk', $this->getUserData());
    }

    public function kurikulum()
    {
        $this->checkRole('kurikulum');
        return view('dashboard/kurikulum', $this->getUserData());
    }

    public function guru()
    {
        $this->checkRole('guru');
        return view('dashboard/guru', $this->getUserData());
    }


    private function checkRole($role)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session()->get('selectedRole') !== $role) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
    }
}
