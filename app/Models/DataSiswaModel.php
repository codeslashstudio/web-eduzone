<?php

namespace App\Models;

use CodeIgniter\Model;

class DataSiswaModel extends Model
{
    protected $table            = 'data_siswa';
    protected $primaryKey       = 'idsiswa';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nama',
        'alamat',
        'agama',
        'idjurusan',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'nis',
        'foto'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    public function getSiswaWithJurusan()
    {
        return $this->select(
            'data_siswa.*, jurusan.kode_jurusan, jurusan.nama_jurusan'
        )
            ->join('jurusan', 'jurusan.idjurusan = data_siswa.idjurusan')
            ->findAll();
    }
    public function getSiswaById($id)
    {
        return $this->select(
            'data_siswa.*, jurusan.kode_jurusan, jurusan.nama_jurusan'
        )
            ->join('jurusan', 'jurusan.idjurusan = data_siswa.idjurusan')
            ->where('data_siswa.idsiswa', $id)
            ->first();
    }
    public function getSiswaByJurusan($idjurusan)
    {
        return $this->where('idjurusan', $idjurusan)->findAll();
    }
}
