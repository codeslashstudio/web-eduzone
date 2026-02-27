<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login',
    ];

    // Timestamps — kolom created_at & updated_at sudah ada di tabel
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|max_length[100]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[superadmin,kepsek,kurikulum,tu,guru_mapel,wali_kelas,kesiswaan,bk,toolman,siswa]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username wajib diisi.',
            'min_length' => 'Username minimal 3 karakter.',
            'is_unique'  => 'Username sudah digunakan.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email sudah terdaftar.',
        ],
        'password' => [
            'required'   => 'Password wajib diisi.',
            'min_length' => 'Password minimal 6 karakter.',
        ],
        'role' => [
            'required' => 'Role wajib dipilih.',
            'in_list'  => 'Role tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    // -------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------

    /**
     * Cari user aktif berdasarkan username
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Cari user aktif berdasarkan email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Update waktu login terakhir
     */
    public function updateLastLogin(int $id): void
    {
        $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }
}