<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table          = 'kelas';
    protected $primaryKey     = 'idkelas';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nama_kelas', 'jurusan', 'tingkat',
        'wali_kelas', 'tahun_ajaran', 'semester',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ==============================
    // LAPORAN AKADEMIK PER KELAS
    // ==============================
    public function getLaporanAkademik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
    {
        try {
            $result = $this->db->query("
                SELECT
                    k.idkelas,
                    k.nama_kelas               AS kelas,
                    k.jurusan,
                    k.tahun_ajaran,
                    k.semester,
                    COUNT(DISTINCT s.idsiswa)  AS jumlah_siswa,
                    ROUND(AVG(n.nilai), 1)     AS rata_rata,
                    COALESCE(MAX(n.nilai), 0)  AS tertinggi,
                    COALESCE(MIN(n.nilai), 0)  AS terendah
                FROM kelas k
                LEFT JOIN students s ON k.idkelas = s.idkelas AND s.is_active = 1
                LEFT JOIN nilai n    ON s.idsiswa  = n.idsiswa
                WHERE k.tahun_ajaran = ?
                  AND k.semester     = ?
                GROUP BY k.idkelas
                ORDER BY k.tingkat ASC, k.jurusan ASC
            ", [$tahunAjaran, $semester])->getResultArray();

            return !empty($result) ? $result : $this->getSampleData();
        } catch (\Exception $e) {
            return $this->getSampleData();
        }
    }

    // ==============================
    // DETAIL SISWA PER KELAS
    // ==============================
    public function getDetailKelas($kelas, $jurusan)
    {
        try {
            $result = $this->db->query("
                SELECT
                    s.idsiswa,
                    s.nis,
                    s.full_name                AS nama,
                    s.gender                   AS jenis_kelamin,
                    ROUND(AVG(n.nilai), 1)     AS rata_rata_nilai,
                    COALESCE(MAX(n.nilai), 0)  AS nilai_tertinggi,
                    COALESCE(MIN(n.nilai), 0)  AS nilai_terendah,
                    k.nama_kelas,
                    k.jurusan,
                    k.wali_kelas
                FROM students s
                JOIN kelas k      ON s.idkelas  = k.idkelas
                LEFT JOIN nilai n ON s.idsiswa   = n.idsiswa
                WHERE k.nama_kelas = ?
                  AND k.jurusan    = ?
                  AND s.is_active  = 1
                GROUP BY s.idsiswa
                ORDER BY rata_rata_nilai DESC
            ", [$kelas, $jurusan])->getResultArray();

            return !empty($result) ? $result : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ==============================
    // STATISTIK KESELURUHAN
    // ==============================
    public function getStatistik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
    {
        try {
            return $this->db->query("
                SELECT
                    COUNT(DISTINCT k.idkelas)  AS total_kelas,
                    COUNT(DISTINCT s.idsiswa)  AS total_siswa,
                    ROUND(AVG(n.nilai), 1)     AS rata_rata_keseluruhan,
                    COALESCE(MAX(n.nilai), 0)  AS nilai_tertinggi,
                    COALESCE(MIN(n.nilai), 0)  AS nilai_terendah
                FROM kelas k
                LEFT JOIN students s ON k.idkelas = s.idkelas AND s.is_active = 1
                LEFT JOIN nilai n    ON s.idsiswa  = n.idsiswa
                WHERE k.tahun_ajaran = ?
                  AND k.semester     = ?
            ", [$tahunAjaran, $semester])->getRowArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // ==============================
    // DATA GRAFIK
    // ==============================
    public function getDataGrafik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
    {
        try {
            return $this->db->query("
                SELECT
                    CONCAT(k.nama_kelas, ' ', k.jurusan) AS label,
                    ROUND(AVG(n.nilai), 1)               AS rata_rata
                FROM kelas k
                LEFT JOIN students s ON k.idkelas = s.idkelas AND s.is_active = 1
                LEFT JOIN nilai n    ON s.idsiswa  = n.idsiswa
                WHERE k.tahun_ajaran = ?
                  AND k.semester     = ?
                GROUP BY k.idkelas
                ORDER BY k.tingkat ASC, k.jurusan ASC
            ", [$tahunAjaran, $semester])->getResultArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // ==============================
    // DISTRIBUSI STATUS KELAS
    // ==============================
    public function getDistribusiStatus($tahunAjaran = '2025/2026', $semester = 'Ganjil')
    {
        try {
            $rows = $this->db->query("
                SELECT
                    CASE
                        WHEN AVG(n.nilai) >= 85 THEN 'Sangat Baik'
                        WHEN AVG(n.nilai) >= 75 THEN 'Baik'
                        WHEN AVG(n.nilai) >= 65 THEN 'Cukup'
                        ELSE 'Perlu Perbaikan'
                    END AS status
                FROM kelas k
                LEFT JOIN students s ON k.idkelas = s.idkelas AND s.is_active = 1
                LEFT JOIN nilai n    ON s.idsiswa  = n.idsiswa
                WHERE k.tahun_ajaran = ?
                  AND k.semester     = ?
                GROUP BY k.idkelas
            ", [$tahunAjaran, $semester])->getResultArray();

            $dist = ['Sangat Baik' => 0, 'Baik' => 0, 'Cukup' => 0, 'Perlu Perbaikan' => 0];
            foreach ($rows as $row) {
                $dist[$row['status']]++;
            }
            return $dist;
        } catch (\Exception $e) {
            return ['Sangat Baik' => 0, 'Baik' => 0, 'Cukup' => 0, 'Perlu Perbaikan' => 0];
        }
    }

    // ==============================
    // PERINGKAT KELAS
    // ==============================
    public function getPeringkatKelas($tahunAjaran = '2025/2026', $semester = 'Ganjil', $limit = 5)
    {
        try {
            return $this->db->query("
                SELECT
                    CONCAT(k.nama_kelas, ' ', k.jurusan) AS kelas,
                    ROUND(AVG(n.nilai), 1)               AS rata_rata,
                    COUNT(DISTINCT s.idsiswa)            AS jumlah_siswa
                FROM kelas k
                LEFT JOIN students s ON k.idkelas = s.idkelas AND s.is_active = 1
                LEFT JOIN nilai n    ON s.idsiswa  = n.idsiswa
                WHERE k.tahun_ajaran = ?
                  AND k.semester     = ?
                GROUP BY k.idkelas
                ORDER BY rata_rata DESC
                LIMIT ?
            ", [$tahunAjaran, $semester, $limit])->getResultArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // ==============================
    // SAMPLE DATA (fallback jika DB kosong)
    // ==============================
    public function getSampleData(): array
    {
        return [
            ['kelas' => 'X',   'jurusan' => 'IPA 1', 'jumlah_siswa' => 36, 'rata_rata' => 85.5, 'tertinggi' => 95.8, 'terendah' => 72.3],
            ['kelas' => 'X',   'jurusan' => 'IPA 2', 'jumlah_siswa' => 34, 'rata_rata' => 83.2, 'tertinggi' => 93.5, 'terendah' => 70.5],
            ['kelas' => 'X',   'jurusan' => 'IPS 1', 'jumlah_siswa' => 35, 'rata_rata' => 81.8, 'tertinggi' => 91.2, 'terendah' => 68.9],
            ['kelas' => 'X',   'jurusan' => 'IPS 2', 'jumlah_siswa' => 33, 'rata_rata' => 80.5, 'tertinggi' => 89.7, 'terendah' => 67.3],
            ['kelas' => 'XI',  'jurusan' => 'IPA 1', 'jumlah_siswa' => 32, 'rata_rata' => 84.3, 'tertinggi' => 94.2, 'terendah' => 71.8],
            ['kelas' => 'XI',  'jurusan' => 'IPA 2', 'jumlah_siswa' => 31, 'rata_rata' => 82.7, 'tertinggi' => 92.8, 'terendah' => 69.5],
            ['kelas' => 'XI',  'jurusan' => 'IPS 1', 'jumlah_siswa' => 34, 'rata_rata' => 79.9, 'tertinggi' => 88.5, 'terendah' => 66.7],
            ['kelas' => 'XI',  'jurusan' => 'IPS 2', 'jumlah_siswa' => 30, 'rata_rata' => 78.6, 'tertinggi' => 87.3, 'terendah' => 65.2],
            ['kelas' => 'XII', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 35, 'rata_rata' => 86.2, 'tertinggi' => 95.5, 'terendah' => 73.5],
            ['kelas' => 'XII', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 33, 'rata_rata' => 84.8, 'tertinggi' => 93.8, 'terendah' => 72.1],
            ['kelas' => 'XII', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 32, 'rata_rata' => 82.4, 'tertinggi' => 90.6, 'terendah' => 70.3],
            ['kelas' => 'XII', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 29, 'rata_rata' => 81.1, 'tertinggi' => 89.2, 'terendah' => 68.8],
        ];
    }
}