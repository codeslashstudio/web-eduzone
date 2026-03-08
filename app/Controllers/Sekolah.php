<?php

namespace App\Controllers;

class Sekolah extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        if ($requireEdit && !in_array(session()->get('role'), ['superadmin', 'tu'])) {
            redirect()->to(base_url('sekolah'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    // ==============================
    // INDEX — lihat info sekolah
    // ==============================
    public function index()
    {
        $this->authCheck();
        $info = $this->db()->query("SELECT * FROM school_info LIMIT 1")->getRowArray();

        return view('sekolah/index', [
            'title'   => 'Info Sekolah',
            'info'    => $info ?? [],
            'canEdit' => in_array(session()->get('role'), ['superadmin', 'tu']),
        ]);
    }

    // ==============================
    // EDIT — form edit
    // ==============================
    public function edit()
    {
        $this->authCheck(true);
        $info = $this->db()->query("SELECT * FROM school_info LIMIT 1")->getRowArray();

        return view('sekolah/form', [
            'title' => 'Edit Info Sekolah',
            'info'  => $info ?? [],
        ]);
    }

    // ==============================
    // UPDATE — simpan perubahan
    // ==============================
    public function update()
    {
        $this->authCheck(true);

        $db   = $this->db();
        $info = $db->query("SELECT id FROM school_info LIMIT 1")->getRowArray();

        $data = [
            'npsn'               => $this->request->getPost('npsn'),
            'name'               => $this->request->getPost('name'),
            'level'              => $this->request->getPost('level'),
            'status'             => $this->request->getPost('status'),
            'accreditation'      => $this->request->getPost('accreditation'),
            'nss'                => $this->request->getPost('nss'),
            'address'            => $this->request->getPost('address'),
            'rt'                 => $this->request->getPost('rt'),
            'rw'                 => $this->request->getPost('rw'),
            'village'            => $this->request->getPost('village'),
            'district'           => $this->request->getPost('district'),
            'city'               => $this->request->getPost('city'),
            'province'           => $this->request->getPost('province'),
            'postal_code'        => $this->request->getPost('postal_code'),
            'phone'              => $this->request->getPost('phone'),
            'fax'                => $this->request->getPost('fax'),
            'email'              => $this->request->getPost('email'),
            'website'            => $this->request->getPost('website'),
            'founded_year'       => $this->request->getPost('founded_year'),
            'principal_name'     => $this->request->getPost('principal_name'),
            'principal_nip'      => $this->request->getPost('principal_nip'),
            'principal_phone'    => $this->request->getPost('principal_phone'),
            'school_hours'       => $this->request->getPost('school_hours'),
            'open_time'          => $this->request->getPost('open_time') ?: null,
            'close_time'         => $this->request->getPost('close_time') ?: null,
            'bank_name'          => $this->request->getPost('bank_name'),
            'bank_branch'        => $this->request->getPost('bank_branch'),
            'bank_account_number'=> $this->request->getPost('bank_account_number'),
            'bank_account_name'  => $this->request->getPost('bank_account_name'),
            'vision'             => $this->request->getPost('vision'),
            'mission'            => $this->request->getPost('mission'),
            'motto'              => $this->request->getPost('motto'),
        ];

        // Upload logo
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/sekolah', $newName);
            $data['logo'] = $newName;
        }

        if ($info) {
            $db->table('school_info')->update($data, ['id' => $info['id']]);
        } else {
            $db->table('school_info')->insert($data);
        }

        return redirect()->to(base_url('sekolah'))->with('success', 'Info sekolah berhasil diperbarui');
    }
}