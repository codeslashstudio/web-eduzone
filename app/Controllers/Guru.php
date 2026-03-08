<?php

namespace App\Controllers;

use App\Models\TeacherModel;
use App\Models\ClassModel;

class Guru extends BaseController
{
    protected $guruModel;
    protected $classModel;

    // Role yang boleh VIEW
    protected $viewRoles = ['kepsek', 'tu', 'superadmin'];

    // Role yang boleh CRUD
    protected $editRoles = ['tu', 'superadmin'];

    public function __construct()
    {
        $this->guruModel  = new TeacherModel();
        $this->classModel = new ClassModel();
        helper(['form', 'url']);
    }

    // ==============================
    // AUTH HELPER
    // ==============================
    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send();
            exit;
        }

        $role = session()->get('role');

        if (!in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send();
            exit;
        }

        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('guru'))->with('error', 'Akses ditolak')->send();
            exit;
        }
    }

    private function canEdit(): bool
    {
        return in_array(session()->get('role'), $this->editRoles);
    }

    // ==============================
    // INDEX — daftar guru
    // ==============================
    public function index()
    {
        $this->authCheck();

        $data = [
            'title'   => 'Data Guru',
            'guru'      => $this->guruModel->getGuruAktif(),
            'kelasList' => $this->classModel->getAll(),
            'canEdit'   => $this->canEdit(),
        ];

        return view('data_guru/index', $data);
    }

    // ==============================
    // ADD — form tambah
    // ==============================
    public function add()
    {
        $this->authCheck(true);

        $data = [
            'title' => 'Tambah Guru',
            'mode'  => 'add',
            'guru'  => [],
        ];

        return view('data_guru/form', $data);
    }

    // ==============================
    // STORE — simpan data baru
    // ==============================
    public function store()
    {
        $this->authCheck(true);

        $rules = [
            'full_name' => [
                'rules'  => 'required|min_length[3]',
                'errors' => ['required' => 'Nama harus diisi', 'min_length' => 'Nama minimal 3 karakter'],
            ],
            'gender' => [
                'rules'  => 'required|in_list[L,P]',
                'errors' => ['required' => 'Jenis kelamin harus dipilih'],
            ],
            'religion' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Agama harus dipilih'],
            ],
            'last_education' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Pendidikan terakhir harus dipilih'],
            ],
            'employment_status' => [
                'rules'  => 'required|in_list[PNS,PPPK,Honorer,GTY,GTT]',
                'errors' => ['required' => 'Status kepegawaian harus dipilih'],
            ],
            'nip' => [
                'rules'  => 'permit_empty|is_unique[teachers.nip]',
                'errors' => ['is_unique' => 'NIP sudah terdaftar'],
            ],
            'email' => [
                'rules'  => 'permit_empty|valid_email|is_unique[teachers.email]',
                'errors' => ['valid_email' => 'Format email tidak valid', 'is_unique' => 'Email sudah terdaftar'],
            ],
            'photo' => [
                'rules'  => 'permit_empty|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
                'errors' => ['max_size' => 'Foto maksimal 2MB', 'is_image' => 'File harus berupa gambar'],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'nip'               => $this->request->getPost('nip'),
            'nuptk'             => $this->request->getPost('nuptk'),
            'full_name'         => $this->request->getPost('full_name'),
            'gender'            => $this->request->getPost('gender'),
            'religion'          => $this->request->getPost('religion'),
            'birth_place'       => $this->request->getPost('birth_place'),
            'birth_date'        => $this->request->getPost('birth_date') ?: null,
            'address'           => $this->request->getPost('address'),
            'phone'             => $this->request->getPost('phone'),
            'email'             => $this->request->getPost('email'),
            'last_education'    => $this->request->getPost('last_education'),
            'education_major'   => $this->request->getPost('education_major'),
            'employment_status' => $this->request->getPost('employment_status'),
            'joined_date'       => $this->request->getPost('joined_date') ?: null,
        ];

        // Upload foto
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads/guru', $newName);
            $saveData['photo'] = $newName;
        }

        if ($this->guruModel->insert($saveData)) {
            return redirect()->to(base_url('guru'))->with('success', 'Data guru berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data guru');
    }

    // ==============================
    // DETAIL — lihat detail
    // ==============================
    public function detail($id)
    {
        $this->authCheck();

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'title'   => 'Detail Guru',
            'guru'    => $guru,
            'canEdit' => $this->canEdit(),
        ];

        return view('data_guru/detail', $data);
    }

    // ==============================
    // EDIT — form edit
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Guru',
            'mode'  => 'edit',
            'guru'  => $guru,
        ];

        return view('data_guru/form', $data);
    }

    // ==============================
    // UPDATE — simpan perubahan
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data guru tidak ditemukan');
        }

        $rules = [
            'full_name' => [
                'rules'  => 'required|min_length[3]',
                'errors' => ['required' => 'Nama harus diisi'],
            ],
            'gender' => [
                'rules'  => 'required|in_list[L,P]',
                'errors' => ['required' => 'Jenis kelamin harus dipilih'],
            ],
            'religion' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Agama harus dipilih'],
            ],
            'last_education' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Pendidikan terakhir harus dipilih'],
            ],
            'employment_status' => [
                'rules'  => 'required|in_list[PNS,PPPK,Honorer,GTY,GTT]',
                'errors' => ['required' => 'Status kepegawaian harus dipilih'],
            ],
            'nip' => [
                'rules'  => "permit_empty|is_unique[teachers.nip,id,{$id}]",
                'errors' => ['is_unique' => 'NIP sudah terdaftar'],
            ],
            'email' => [
                'rules'  => "permit_empty|valid_email|is_unique[teachers.email,id,{$id}]",
                'errors' => ['valid_email' => 'Format email tidak valid', 'is_unique' => 'Email sudah terdaftar'],
            ],
            'photo' => [
                'rules'  => 'permit_empty|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
                'errors' => ['max_size' => 'Foto maksimal 2MB', 'is_image' => 'File harus berupa gambar'],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'nip'               => $this->request->getPost('nip'),
            'nuptk'             => $this->request->getPost('nuptk'),
            'full_name'         => $this->request->getPost('full_name'),
            'gender'            => $this->request->getPost('gender'),
            'religion'          => $this->request->getPost('religion'),
            'birth_place'       => $this->request->getPost('birth_place'),
            'birth_date'        => $this->request->getPost('birth_date') ?: null,
            'address'           => $this->request->getPost('address'),
            'phone'             => $this->request->getPost('phone'),
            'email'             => $this->request->getPost('email'),
            'last_education'    => $this->request->getPost('last_education'),
            'education_major'   => $this->request->getPost('education_major'),
            'employment_status' => $this->request->getPost('employment_status'),
            'joined_date'       => $this->request->getPost('joined_date') ?: null,
        ];

        // Upload foto baru
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Hapus foto lama
            if (!empty($guru['photo'])) {
                $oldPath = ROOTPATH . 'public/uploads/guru/' . $guru['photo'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads/guru', $newName);
            $saveData['photo'] = $newName;
        }

        if ($this->guruModel->update($id, $saveData)) {
            return redirect()->to(base_url('guru/detail/' . $id))->with('success', 'Data guru berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data guru');
    }

    // ==============================
    // DELETE — hapus data
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('guru'));
        }

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data guru tidak ditemukan');
        }

        // Hapus foto jika ada
        if (!empty($guru['photo'])) {
            $path = ROOTPATH . 'public/uploads/guru/' . $guru['photo'];
            if (file_exists($path)) unlink($path);
        }

        if ($this->guruModel->delete($id)) {
            return redirect()->to(base_url('guru'))->with('success', 'Data guru ' . $guru['full_name'] . ' berhasil dihapus');
        }

        return redirect()->to(base_url('guru'))->with('error', 'Gagal menghapus data guru');
    }
}