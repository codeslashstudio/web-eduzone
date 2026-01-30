<?php

namespace App\Models;

use CodeIgniter\Model;

class MataPelajaranModel extends Model
{
    protected $table            = 'mata_pelajaran';
    protected $primaryKey       = 'idmapel';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'kode_mapel',
        'nama_mapel',
        'idjurusan',
        'kelompok',
        'jam_pelajaran',
        'is_active'
    ];

    protected $useTimestamps = false;

    // ==============================
    // MAPEL + JURUSAN
    // ==============================
    public function getMapelWithJurusan()
    {
        return $this->select(
            'mata_pelajaran.*, jurusan.kode_jurusan, jurusan.nama_jurusan'
        )
            ->join('jurusan', 'jurusan.idjurusan = mata_pelajaran.idjurusan')
            ->where('mata_pelajaran.is_active', 1)
            ->findAll();
    }

    // ==============================
    // MAPEL PER JURUSAN
    // ==============================
    public function getMapelByJurusan($idjurusan)
    {
        return $this->where([
            'idjurusan' => $idjurusan,
            'is_active' => 1
        ])->findAll();
    }

    // ==============================
    // DETAIL MAPEL
    // ==============================
    public function getMapelById($id)
    {
        return $this->where('idmapel', $id)->first();
    }
}
