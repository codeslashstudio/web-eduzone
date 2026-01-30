<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table            = 'jurusan';
    protected $primaryKey       = 'idjurusan';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'kode_jurusan',
        'nama_jurusan',
        'keterangan',
        'is_active'
    ];

    protected $useTimestamps = false;

    // ==============================
    // AMBIL JURUSAN AKTIF
    // ==============================
    public function getJurusanAktif()
    {
        return $this->where('is_active', 1)->findAll();
    }

    // ==============================
    // DETAIL JURUSAN
    // ==============================
    public function getJurusanById($id)
    {
        return $this->where('idjurusan', $id)->first();
    }
}
