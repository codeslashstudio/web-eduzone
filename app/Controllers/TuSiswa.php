<?php

namespace App\Controllers;

use App\Models\DataSiswaModel;
use App\Models\JurusanModel;

class TuSiswa extends BaseController
{
    protected $siswaModel;
    protected $jurusanModel;


    public function __construct()
    {
        $this->siswaModel = new DataSiswaModel();
        $this->jurusanModel = new JurusanModel();
        helper(['form', 'url']);
    }

    private function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session()->get('selectedRole') !== 'tu') {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        return null;
    }

    private function getUserData()
    {
        return [
            'username' => session()->get('fullname'),
            'email'    => session()->get('email'),
            'role'     => session()->get('role'),
        ];
    }

    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = $this->getUserData();
        $data['siswa'] = $this->siswaModel->getSiswaWithJurusan();
        $data['title'] = 'Data Siswa';

        return view('Tu/siswa/index', $data);
    }

    public function add()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = $this->getUserData();
        $data['jurusan'] = $this->jurusanModel->findAll();
        $data['title'] = 'Tambah Siswa';
        $data['validation'] = \Config\Services::validation();

        return view('Tu/siswa/add', $data);
    }

    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Validation rules
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'nis' => [
                'rules' => 'required|exact_length[10]|numeric|is_unique[data_siswa.nis]',
                'errors' => [
                    'required' => 'NISN harus diisi',
                    'exact_length' => 'NISN harus 10 digit',
                    'numeric' => 'NISN harus berupa angka',
                    'is_unique' => 'NISN sudah terdaftar'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus diisi'
                ]
            ],
            'agama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Agama harus dipilih'
                ]
            ],
            'idjurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jurusan harus dipilih'
                ]
            ],
            'tanggal_lahir' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal lahir harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'nama_ayah' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama ayah harus diisi',
                    'min_length' => 'Nama ayah minimal 3 karakter',
                    'max_length' => 'Nama ayah maksimal 100 karakter'
                ]
            ],
            'nama_ibu' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama ibu harus diisi',
                    'min_length' => 'Nama ibu minimal 3 karakter',
                    'max_length' => 'Nama ibu maksimal 100 karakter'
                ]
            ],
            'foto' => [
                'rules' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Foto harus diupload',
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file upload
        $foto = $this->request->getFile('foto');
        $fotoName = '';

        if ($foto->isValid() && !$foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/siswa', $fotoName);
        }

        // Prepare data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nis' => $this->request->getPost('nis'),
            'alamat' => $this->request->getPost('alamat'),
            'agama' => $this->request->getPost('agama'),
            'idjurusan' => $this->request->getPost('idjurusan'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'nama_ayah' => $this->request->getPost('nama_ayah'),
            'nama_ibu' => $this->request->getPost('nama_ibu'),
            'foto' => $fotoName
        ];

        // Insert data
        if ($this->siswaModel->insert($data)) {
            return redirect()->to('/tu/siswa')->with('success', 'Data siswa berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data siswa');
        }
    }

    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = $this->getUserData();
        $data['siswa'] = $this->siswaModel->find($id);
        $data['jurusan'] = $this->jurusanModel->findAll();
        $data['title'] = 'Edit Siswa';
        $data['validation'] = \Config\Services::validation();

        if (!$data['siswa']) {
            return redirect()->to('/tu/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        return view('Tu/siswa/edit', $data);
    }

    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/tu/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'nis' => [
                'rules' => "required|exact_length[10]|numeric|is_unique[data_siswa.nis,idsiswa,{$id}]",
                'errors' => [
                    'required' => 'NISN harus diisi',
                    'exact_length' => 'NISN harus 10 digit',
                    'numeric' => 'NISN harus berupa angka',
                    'is_unique' => 'NISN sudah terdaftar'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus diisi'
                ]
            ],
            'agama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Agama harus dipilih'
                ]
            ],
            'idjurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jurusan harus dipilih'
                ]
            ],
            'tanggal_lahir' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal lahir harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'nama_ayah' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama ayah harus diisi',
                    'min_length' => 'Nama ayah minimal 3 karakter',
                    'max_length' => 'Nama ayah maksimal 100 karakter'
                ]
            ],
            'nama_ibu' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama ibu harus diisi',
                    'min_length' => 'Nama ibu minimal 3 karakter',
                    'max_length' => 'Nama ibu maksimal 100 karakter'
                ]
            ],
            'foto' => [
                'rules' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file upload
        $foto = $this->request->getFile('foto');
        $fotoName = $siswa['foto']; // Keep old photo by default

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Delete old photo if exists
            if ($siswa['foto'] && file_exists(ROOTPATH . 'public/uploads/siswa/' . $siswa['foto'])) {
                unlink(ROOTPATH . 'public/uploads/siswa/' . $siswa['foto']);
            }

            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/siswa', $fotoName);
        }

        // Prepare data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nis' => $this->request->getPost('nis'),
            'alamat' => $this->request->getPost('alamat'),
            'agama' => $this->request->getPost('agama'),
            'idjurusan' => $this->request->getPost('idjurusan'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'nama_ayah' => $this->request->getPost('nama_ayah'),
            'nama_ibu' => $this->request->getPost('nama_ibu'),
            'foto' => $fotoName
        ];

        // Update data
        if ($this->siswaModel->update($id, $data)) {
            return redirect()->to('/tu/siswa')->with('success', 'Data siswa berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data siswa');
        }
    }

    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/tu/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        // Delete photo if exists
        if ($siswa['foto'] && file_exists(ROOTPATH . 'public/uploads/siswa/' . $siswa['foto'])) {
            unlink(ROOTPATH . 'public/uploads/siswa/' . $siswa['foto']);
        }

        if ($this->siswaModel->delete($id)) {
            return redirect()->to('/tu/siswa')->with('success', 'Data siswa berhasil dihapus');
        } else {
            return redirect()->to('/tu/siswa')->with('error', 'Gagal menghapus data siswa');
        }
    }

    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = $this->getUserData();
        $data['siswa'] = $this->siswaModel->getSiswaById($id);
        $data['title'] = 'Detail Siswa';

        if (!$data['siswa']) {
            return redirect()->to('/tu/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        return view('Tu/siswa/detail', $data);
    }
}