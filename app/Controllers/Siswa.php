<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\MajorModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $majorModel;

    // Role yang boleh LIHAT data siswa
    protected $viewRoles = ['kepsek', 'tu', 'kurikulum', 'kesiswaan', 'bk', 'wakel', 'superadmin'];

    // Role yang boleh EDIT/TAMBAH/HAPUS data siswa
    protected $editRoles = ['tu', 'superadmin'];

    public function __construct()
    {
        $this->siswaModel = new StudentModel();
        $this->majorModel = new MajorModel();
        helper(['form', 'url']);
    }

    // ==============================
    // HELPER: Ambil prefix URL sesuai role
    // Dipakai untuk redirect yang benar setelah aksi CRUD
    // ==============================
    private function getRolePrefix(): string
    {
        $role = session()->get('role');

        $prefixMap = [
            'kepsek'    => 'kepsek',
            'tu'        => 'tu',
            'kurikulum' => 'kurikulum',
            'kesiswaan' => 'kesiswaan',
            'bk'        => 'bk',
            'wakel'     => 'wakel',
            'superadmin' => 'superadmin', // superadmin punya prefix sendiri
        ];

        return $prefixMap[$role] ?? 'dashboard';
    }

    // ==============================
    // HELPER: Cek autentikasi dan hak akses
    // ==============================
    private function checkAuth(bool $requireEdit = false)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $role   = session()->get('role');
        $prefix = $this->getRolePrefix();

        if (!$requireEdit && !in_array($role, $this->viewRoles)) {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }

        if ($requireEdit && !in_array($role, $this->editRoles)) {
            // Redirect ke halaman list siswa sesuai role masing-masing
            return redirect()->to("/{$prefix}/siswa")->with('error', 'Akses tidak diizinkan');
        }

        return null;
    }

    // ==============================
    // HELPER: URL dashboard sesuai role
    // ==============================
    private function getDashboardUrl(): string
    {
        $role = session()->get('role');
        $map = [
            'kepsek'     => 'dashboard/kepsek',
            'tu'         => 'dashboard/tu',
            'kurikulum'  => 'dashboard/kurikulum',
            'kesiswaan'  => 'dashboard/kesiswaan',
            'bk'         => 'dashboard/bk',
            'wakel'      => 'dashboard/wakel',
            'superadmin' => 'dashboard',
        ];
        return $map[$role] ?? 'dashboard';
    }

    // ==============================
    // HELPER: Data user untuk view
    // ==============================
    private function getUserData(): array
    {
        $role = session()->get('role');
        return [
            'username'     => session()->get('username'),
            'email'        => session()->get('email'),
            'role'         => $role,
            'rolePrefix'   => $this->getRolePrefix(),
            'dashboardUrl' => $this->getDashboardUrl(),
            'canEdit'      => in_array($role, $this->editRoles),
        ];
    }

    // ==============================
    // LIST SISWA
    // ==============================
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data          = $this->getUserData();
        $data['siswa'] = $this->siswaModel->getSiswaWithMajor();
        $data['title'] = 'Data Siswa';

        return view('data_siswa/index', $data);
    }

    // ==============================
    // DETAIL SISWA
    // ==============================
    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $prefix        = $this->getRolePrefix();
        $data          = $this->getUserData();
        $data['siswa'] = $this->siswaModel->getSiswaById($id);
        $data['title'] = 'Detail Siswa';

        if (!$data['siswa']) {
            return redirect()->to("/{$prefix}/siswa")->with('error', 'Data siswa tidak ditemukan');
        }

        return view('data_siswa/detail', $data);
    }

    // ==============================
    // FORM TAMBAH (hanya editRoles)
    // ==============================
    public function add()
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $data            = $this->getUserData();
        $data['jurusan'] = $this->majorModel->where('is_active', 1)->findAll();
        $data['title']   = 'Tambah Siswa';
        $data['mode']    = 'add';
        $data['siswa']   = null;

        return view('data_siswa/form', $data);
    }

    // ==============================
    // SIMPAN DATA BARU (hanya editRoles)
    // ==============================
    public function store()
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $prefix = $this->getRolePrefix();

        $rules = [
            'full_name'  => ['rules' => 'required|min_length[3]|max_length[100]',
                             'errors' => ['required' => 'Nama harus diisi']],
            'nisn'       => ['rules' => 'required|max_length[20]|is_unique[students.nisn]',
                             'errors' => ['required' => 'NISN harus diisi', 'is_unique' => 'NISN sudah terdaftar']],
            'nis'        => ['rules' => 'permit_empty|max_length[20]|is_unique[students.nis]',
                             'errors' => ['is_unique' => 'NIS sudah terdaftar']],
            'gender'     => ['rules' => 'required|in_list[L,P]',
                             'errors' => ['required' => 'Jenis kelamin harus dipilih']],
            'birth_date' => ['rules' => 'required|valid_date',
                             'errors' => ['required' => 'Tanggal lahir harus diisi']],
            'religion'   => ['rules' => 'required',
                             'errors' => ['required' => 'Agama harus dipilih']],
            'address'    => ['rules' => 'required',
                             'errors' => ['required' => 'Alamat harus diisi']],
            'grade'      => ['rules' => 'required|in_list[X,XI,XII]',
                             'errors' => ['required' => 'Kelas harus dipilih']],
            'major_id'   => ['rules' => 'required',
                             'errors' => ['required' => 'Jurusan harus dipilih']],
            'father_name' => ['rules' => 'required|min_length[3]',
                              'errors' => ['required' => 'Nama ayah harus diisi']],
            'mother_name' => ['rules' => 'required|min_length[3]',
                              'errors' => ['required' => 'Nama ibu harus diisi']],
            'photo'      => ['rules' => 'permit_empty|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
                             'errors' => ['max_size' => 'Foto maksimal 2MB']],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photoName = $this->uploadPhoto();

        $insertData = [
            'user_id'      => 0,
            'full_name'    => $this->request->getPost('full_name'),
            'nis'          => $this->request->getPost('nis'),
            'nisn'         => $this->request->getPost('nisn'),
            'gender'       => $this->request->getPost('gender'),
            'birth_place'  => $this->request->getPost('birth_place'),
            'birth_date'   => $this->request->getPost('birth_date'),
            'religion'     => $this->request->getPost('religion'),
            'address'      => $this->request->getPost('address'),
            'phone'        => $this->request->getPost('phone'),
            'grade'        => $this->request->getPost('grade'),
            'major_id'     => $this->request->getPost('major_id'),
            'class_group'  => $this->request->getPost('class_group'),
            'father_name'  => $this->request->getPost('father_name'),
            'mother_name'  => $this->request->getPost('mother_name'),
            'father_job'   => $this->request->getPost('father_job'),
            'mother_job'   => $this->request->getPost('mother_job'),
            'parent_phone' => $this->request->getPost('parent_phone'),
            'status'       => 'aktif',
            'photo'        => $photoName,
        ];

        if ($this->siswaModel->insert($insertData)) {
            return redirect()->to("/{$prefix}/siswa")->with('success', 'Data siswa berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data siswa');
    }

    // ==============================
    // FORM EDIT (hanya editRoles)
    // ==============================
    public function edit($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $prefix = $this->getRolePrefix();
        $siswa  = $this->siswaModel->getSiswaById($id);

        if (!$siswa) {
            return redirect()->to("/{$prefix}/siswa")->with('error', 'Data siswa tidak ditemukan');
        }

        $data            = $this->getUserData();
        $data['siswa']   = $siswa;
        $data['jurusan'] = $this->majorModel->where('is_active', 1)->findAll();
        $data['title']   = 'Edit Siswa';
        $data['mode']    = 'edit';

        return view('data_siswa/form', $data);
    }

    // ==============================
    // UPDATE DATA (hanya editRoles)
    // ==============================
    public function update($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $prefix = $this->getRolePrefix();
        $siswa  = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to("/{$prefix}/siswa")->with('error', 'Data siswa tidak ditemukan');
        }

        $rules = [
            'full_name'   => ['rules' => 'required|min_length[3]|max_length[100]',
                              'errors' => ['required' => 'Nama harus diisi']],
            'nisn'        => ['rules' => "required|max_length[20]|is_unique[students.nisn,id,{$id}]",
                              'errors' => ['required' => 'NISN harus diisi', 'is_unique' => 'NISN sudah terdaftar']],
            'nis'         => ['rules' => "permit_empty|max_length[20]|is_unique[students.nis,id,{$id}]"],
            'gender'      => ['rules' => 'required|in_list[L,P]'],
            'birth_date'  => ['rules' => 'required|valid_date'],
            'religion'    => ['rules' => 'required'],
            'address'     => ['rules' => 'required'],
            'grade'       => ['rules' => 'required|in_list[X,XI,XII]'],
            'major_id'    => ['rules' => 'required'],
            'father_name' => ['rules' => 'required|min_length[3]'],
            'mother_name' => ['rules' => 'required|min_length[3]'],
            'photo'       => ['rules' => 'permit_empty|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photoName  = $this->uploadPhoto($siswa['photo']);
        $updateData = [
            'full_name'    => $this->request->getPost('full_name'),
            'nis'          => $this->request->getPost('nis'),
            'nisn'         => $this->request->getPost('nisn'),
            'gender'       => $this->request->getPost('gender'),
            'birth_place'  => $this->request->getPost('birth_place'),
            'birth_date'   => $this->request->getPost('birth_date'),
            'religion'     => $this->request->getPost('religion'),
            'address'      => $this->request->getPost('address'),
            'phone'        => $this->request->getPost('phone'),
            'grade'        => $this->request->getPost('grade'),
            'major_id'     => $this->request->getPost('major_id'),
            'class_group'  => $this->request->getPost('class_group'),
            'father_name'  => $this->request->getPost('father_name'),
            'mother_name'  => $this->request->getPost('mother_name'),
            'father_job'   => $this->request->getPost('father_job'),
            'mother_job'   => $this->request->getPost('mother_job'),
            'parent_phone' => $this->request->getPost('parent_phone'),
            'photo'        => $photoName,
        ];

        if ($this->siswaModel->update($id, $updateData)) {
            return redirect()->to("/{$prefix}/siswa")->with('success', 'Data siswa berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data siswa');
    }

    // ==============================
    // HAPUS SISWA (hanya editRoles)
    // ==============================
    public function delete($id)
    {
        $authCheck = $this->checkAuth(true);
        if ($authCheck) return $authCheck;

        $prefix = $this->getRolePrefix();
        $siswa  = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to("/{$prefix}/siswa")->with('error', 'Data siswa tidak ditemukan');
        }

        if (!empty($siswa['photo']) && file_exists(ROOTPATH . 'public/uploads/siswa/' . $siswa['photo'])) {
            unlink(ROOTPATH . 'public/uploads/siswa/' . $siswa['photo']);
        }

        if ($this->siswaModel->delete($id)) {
            return redirect()->to("/{$prefix}/siswa")->with('success', 'Data siswa berhasil dihapus');
        }

        return redirect()->to("/{$prefix}/siswa")->with('error', 'Gagal menghapus data siswa');
    }

    // ==============================
    // HELPER: Upload foto
    // ==============================
    private function uploadPhoto(string $oldPhoto = ''): string
    {
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if ($oldPhoto && file_exists(ROOTPATH . 'public/uploads/siswa/' . $oldPhoto)) {
                unlink(ROOTPATH . 'public/uploads/siswa/' . $oldPhoto);
            }
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads/siswa', $newName);
            return $newName;
        }

        return $oldPhoto;
    }
}