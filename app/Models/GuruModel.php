<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'guru';
    protected $primaryKey       = 'idguru';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nip',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'agama',
        'no_hp',
        'email',
        'pendidikan_terakhir',
        'jabatan',
        'status_kepegawaian',
        'foto',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ==============================
    // AMBIL SEMUA GURU AKTIF
    // ==============================
    public function getGuruAktif()
    {
        return $this->where('is_active', 1)->findAll();
    }

    // ==============================
    // DETAIL GURU
    // ==============================
    public function getGuruById($id)
    {
        return $this->where('idguru', $id)->first();
    }

    // ==============================
    // NONAKTIFKAN GURU
    // ==============================
    public function nonaktifkanGuru($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }
}
