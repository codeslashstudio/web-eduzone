<?php

namespace App\Controllers;

use App\Models\GuruModel;

class TuGuru extends BaseController
{
    protected $guruModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        helper(['form', 'url']);
    }

    // ==============================
    // TAMPILKAN DAFTAR GURU
    // ==============================
    public function index()
    {
        $this->checkRole('tu');

        $data = [
            'username' => session()->get('fullname'),
            'guru' => $this->guruModel->getGuruAktif()
        ];

        return view('Tu/guru/index', $data);
    }

    // ==============================
    // FORM TAMBAH GURU
    // ==============================
    public function add()
    {
        $this->checkRole('tu');

        $data = [
            'username' => session()->get('fullname')
        ];

        return view('Tu/guru/add', $data);
    }

    // ==============================
    // SIMPAN DATA GURU BARU
    // ==============================
    public function store()
    {
        $this->checkRole('tu');

        // Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nip' => [
                'rules' => 'required|is_unique[guru.nip]',
                'errors' => [
                    'required' => 'NIP harus diisi',
                    'is_unique' => 'NIP sudah terdaftar'
                ]
            ],
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin tidak valid'
                ]
            ],
            'agama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Agama harus dipilih'
                ]
            ],
            'no_hp' => [
                'rules' => 'required|numeric|min_length[10]|max_length[13]',
                'errors' => [
                    'required' => 'No. HP harus diisi',
                    'numeric' => 'No. HP harus berupa angka',
                    'min_length' => 'No. HP minimal 10 digit',
                    'max_length' => 'No. HP maksimal 13 digit'
                ]
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email|is_unique[guru.email]',
                'errors' => [
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'pendidikan_terakhir' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pendidikan terakhir harus dipilih'
                ]
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jabatan harus diisi'
                ]
            ],
            'status_kepegawaian' => [
                'rules' => 'required|in_list[PNS,PPPK,Honorer,Kontrak]',
                'errors' => [
                    'required' => 'Status kepegawaian harus dipilih',
                    'in_list' => 'Status kepegawaian tidak valid'
                ]
            ],
            'foto' => [
                'rules' => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'File foto harus diupload',
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data
        $data = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'agama' => $this->request->getPost('agama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'email' => $this->request->getPost('email'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'jabatan' => $this->request->getPost('jabatan'),
            'status_kepegawaian' => $this->request->getPost('status_kepegawaian'),
            'is_active' => 1
        ];

        // Handle file upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/guru', $newName);
            $data['foto'] = $newName;
        }

        // Save to database
        if ($this->guruModel->insert($data)) {
            return redirect()->to(base_url('tu/guru'))->with('success', 'Data guru berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data guru');
        }
    }

    // ==============================
    // DETAIL GURU
    // ==============================
    public function detail($id)
    {
        $this->checkRole('tu');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'username' => session()->get('fullname'),
            'guru' => $guru
        ];

        return view('Tu/guru/detail', $data);
    }

    // ==============================
    // FORM EDIT GURU
    // ==============================
    public function edit($id)
    {
        $this->checkRole('tu');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'username' => session()->get('fullname'),
            'guru' => $guru
        ];

        return view('Tu/guru/edit', $data);
    }

    // ==============================
    // UPDATE DATA GURU
    // ==============================
    public function update($id)
    {
        $this->checkRole('tu');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Data guru tidak ditemukan');
        }

        // Validasi Input
        $validation = \Config\Services::validation();
        $rules = [
            'nip' => [
                'rules' => "required|is_unique[guru.nip,idguru,{$id}]",
                'errors' => [
                    'required' => 'NIP harus diisi',
                    'is_unique' => 'NIP sudah terdaftar'
                ]
            ],
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin tidak valid'
                ]
            ],
            'agama' => [
                'rules' => 'required',
                'errors' => ['required' => 'Agama harus dipilih']
            ],
            'no_hp' => [
                'rules' => 'required|numeric|min_length[10]|max_length[13]',
                'errors' => [
                    'required' => 'No. HP harus diisi',
                    'numeric' => 'No. HP harus berupa angka',
                    'min_length' => 'No. HP minimal 10 digit',
                    'max_length' => 'No. HP maksimal 13 digit'
                ]
            ],
            'email' => [
                'rules' => "permit_empty|valid_email|is_unique[guru.email,idguru,{$id}]",
                'errors' => [
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'pendidikan_terakhir' => [
                'rules' => 'required',
                'errors' => ['required' => 'Pendidikan terakhir harus dipilih']
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => ['required' => 'Jabatan harus diisi']
            ],
            'status_kepegawaian' => [
                'rules' => 'required|in_list[PNS,PPPK,Honorer,Kontrak]',
                'errors' => [
                    'required' => 'Status kepegawaian harus dipilih',
                    'in_list' => 'Status kepegawaian tidak valid'
                ]
            ],
            'foto' => [
                'rules' => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
                ]
            ]
        ];

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data
        $data = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'agama' => $this->request->getPost('agama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'email' => $this->request->getPost('email'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'jabatan' => $this->request->getPost('jabatan'),
            'status_kepegawaian' => $this->request->getPost('status_kepegawaian')
        ];

        // Handle file upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Delete old photo
            if (!empty($guru['foto']) && file_exists(ROOTPATH . 'public/uploads/guru/' . $guru['foto'])) {
                unlink(ROOTPATH . 'public/uploads/guru/' . $guru['foto']);
            }

            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/guru', $newName);
            $data['foto'] = $newName;
        }

        // Update to database
        if ($this->guruModel->update($id, $data)) {
            return redirect()->to(base_url('tu/guru'))->with('success', 'Data guru berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data guru');
        }
    }

    // ==============================
    // HAPUS GURU (SOFT DELETE)
    // ==============================
    public function delete($id)
    {
        $this->checkRole('tu');

        // Only allow POST method for delete
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Metode tidak diizinkan');
        }

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Data guru tidak ditemukan');
        }

        // Soft delete (set is_active = 0)
        if ($this->guruModel->nonaktifkanGuru($id)) {
            // Log activity
            log_message('info', 'Guru deleted: ' . $guru['nama'] . ' (ID: ' . $id . ') by ' . session()->get('fullname'));

            return redirect()->to(base_url('tu/guru'))->with('success', 'Data guru ' . $guru['nama'] . ' berhasil dihapus');
        } else {
            return redirect()->to(base_url('tu/guru'))->with('error', 'Gagal menghapus data guru');
        }
    }

    // ==============================
    // CHECK USER ROLE
    // ==============================
    private function checkRole($role)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session()->get('selectedRole') !== $role && session()->get('role') !== $role) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
    }
}
