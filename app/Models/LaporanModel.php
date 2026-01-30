<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class LaporanModel extends Model
    {
        protected $table            = 'kelas';
        protected $primaryKey       = 'idkelas';
        protected $returnType       = 'array';
        protected $useSoftDeletes   = false;

        protected $allowedFields = [
            'nama_kelas',
            'jurusan',
            'tingkat',
            'wali_kelas',
            'tahun_ajaran',
            'semester'
        ];

        protected $useTimestamps = true;
        protected $createdField  = 'created_at';
        protected $updatedField  = 'updated_at';

        // ==============================
        // GET LAPORAN AKADEMIK PER KELAS
        // ==============================
        public function getLaporanAkademik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
        {
            return $this->db->query("
            SELECT 
                k.nama_kelas AS kelas,
                k.jurusan,
                COUNT(DISTINCT s.idsiswa) AS jumlah_siswa,
                ROUND(AVG(n.nilai), 1) AS rata_rata,
                MAX(n.nilai) AS nilai_tertinggi,
                MIN(n.nilai) AS nilai_terendah,
                k.tahun_ajaran,
                k.semester
            FROM kelas k
            LEFT JOIN data_siswa s ON k.idkelas = s.idkelas AND s.is_active = 1
            LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
            WHERE k.tahun_ajaran = ?
            AND k.semester = ?
            GROUP BY k.idkelas
            ORDER BY k.tingkat ASC, k.jurusan ASC
        ", [$tahunAjaran, $semester])->getResultArray();
        }


        // ==============================
        // GET DETAIL KELAS
        // ==============================
        public function getDetailKelas($kelas, $jurusan)
        {
            return $this->db->query("
            SELECT 
                s.idsiswa,
                s.nis,
                s.nama,
                s.jenis_kelamin,
                ROUND(AVG(n.nilai), 1) AS rata_rata_nilai,
                MAX(n.nilai) AS nilai_tertinggi,
                MIN(n.nilai) AS nilai_terendah,
                k.nama_kelas,
                k.jurusan,
                k.wali_kelas
            FROM data_siswa s
            JOIN kelas k ON s.idkelas = k.idkelas
            LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
            WHERE k.nama_kelas = ?
            AND k.jurusan = ?
            AND s.is_active = 1
            GROUP BY s.idsiswa
            ORDER BY rata_rata_nilai DESC
        ", [$kelas, $jurusan])->getResultArray();
        }


        // ==============================
        // GET STATISTIK KESELURUHAN
        // ==============================
        public function getStatistik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
        {
            return $this->db->query("
            SELECT 
                COUNT(DISTINCT k.idkelas) AS total_kelas,
                COUNT(DISTINCT s.idsiswa) AS total_siswa,
                ROUND(AVG(n.nilai), 1) AS rata_rata_keseluruhan,
                MAX(n.nilai) AS nilai_tertinggi,
                MIN(n.nilai) AS nilai_terendah
            FROM kelas k
            LEFT JOIN data_siswa s ON k.idkelas = s.idkelas AND s.is_active = 1
            LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
            WHERE k.tahun_ajaran = ?
            AND k.semester = ?
        ", [$tahunAjaran, $semester])->getRowArray();
        }


        // ==============================
        // GET DATA UNTUK GRAFIK
        // ==============================
        public function getDataGrafik($tahunAjaran = '2025/2026', $semester = 'Ganjil')
        {
            return $this->db->query("
            SELECT 
                CONCAT(k.nama_kelas, ' ', k.jurusan) AS label,
                ROUND(AVG(n.nilai), 1) AS rata_rata
            FROM kelas k
            LEFT JOIN data_siswa s ON k.idkelas = s.idkelas AND s.is_active = 1
            LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
            WHERE k.tahun_ajaran = ?
            AND k.semester = ?
            GROUP BY k.idkelas
            ORDER BY k.tingkat ASC, k.jurusan ASC
        ", [$tahunAjaran, $semester])->getResultArray();
        }

        // ==============================
        // GET DISTRIBUSI STATUS
        // ==============================
        public function getDistribusiStatus($tahunAjaran = '2025/2026', $semester = 'Ganjil')
        {
            $query = $this->db->query("
                SELECT 
                    CASE 
                        WHEN AVG(n.nilai) >= 85 THEN 'Sangat Baik'
                        WHEN AVG(n.nilai) >= 75 THEN 'Baik'
                        WHEN AVG(n.nilai) >= 65 THEN 'Cukup'
                        ELSE 'Perlu Perbaikan'
                    END as status,
                    COUNT(*) as jumlah
                FROM kelas k
                LEFT JOIN siswa s ON k.idkelas = s.idkelas
                LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
                WHERE k.tahun_ajaran = ?
                AND k.semester = ?
                AND s.is_active = 1
                GROUP BY k.idkelas
            ", [$tahunAjaran, $semester]);

            $result = $query->getResultArray();

            // Count distribution
            $distribution = [
                'Sangat Baik' => 0,
                'Baik' => 0,
                'Cukup' => 0,
                'Perlu Perbaikan' => 0
            ];

            foreach ($result as $row) {
                $distribution[$row['status']]++;
            }

            return $distribution;
        }

        // ==============================
        // GET PERINGKAT KELAS
        // ==============================
        public function getPeringkatKelas($tahunAjaran = '2025/2026', $semester = 'Ganjil', $limit = 5)
        {
            return $this->db->query("
            SELECT 
                CONCAT(k.nama_kelas, ' ', k.jurusan) AS kelas,
                ROUND(AVG(n.nilai), 1) AS rata_rata,
                COUNT(DISTINCT s.idsiswa) AS jumlah_siswa
            FROM kelas k
            LEFT JOIN data_siswa s ON k.idkelas = s.idkelas AND s.is_active = 1
            LEFT JOIN nilai n ON s.idsiswa = n.idsiswa
            WHERE k.tahun_ajaran = ?
            AND k.semester = ?
            GROUP BY k.idkelas
            ORDER BY rata_rata DESC
            LIMIT ?
        ", [$tahunAjaran, $semester, $limit])->getResultArray();
        }
        public function getSampleData()
        {
            return [
                ['kelas' => 'X', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 36, 'rata_rata' => 85.5, 'nilai_tertinggi' => 95.8, 'nilai_terendah' => 72.3],
                ['kelas' => 'X', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 34, 'rata_rata' => 83.2, 'nilai_tertinggi' => 93.5, 'nilai_terendah' => 70.5],
                ['kelas' => 'X', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 35, 'rata_rata' => 81.8, 'nilai_tertinggi' => 91.2, 'nilai_terendah' => 68.9],
                ['kelas' => 'X', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 33, 'rata_rata' => 80.5, 'nilai_tertinggi' => 89.7, 'nilai_terendah' => 67.3],
                ['kelas' => 'XI', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 32, 'rata_rata' => 84.3, 'nilai_tertinggi' => 94.2, 'nilai_terendah' => 71.8],
                ['kelas' => 'XI', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 31, 'rata_rata' => 82.7, 'nilai_tertinggi' => 92.8, 'nilai_terendah' => 69.5],
                ['kelas' => 'XI', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 34, 'rata_rata' => 79.9, 'nilai_tertinggi' => 88.5, 'nilai_terendah' => 66.7],
                ['kelas' => 'XI', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 30, 'rata_rata' => 78.6, 'nilai_tertinggi' => 87.3, 'nilai_terendah' => 65.2],
                ['kelas' => 'XII', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 35, 'rata_rata' => 86.2, 'nilai_tertinggi' => 95.5, 'nilai_terendah' => 73.5],
                ['kelas' => 'XII', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 33, 'rata_rata' => 84.8, 'nilai_tertinggi' => 93.8, 'nilai_terendah' => 72.1],
                ['kelas' => 'XII', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 32, 'rata_rata' => 82.4, 'nilai_tertinggi' => 90.6, 'nilai_terendah' => 70.3],
                ['kelas' => 'XII', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 29, 'rata_rata' => 81.1, 'nilai_tertinggi' => 89.2, 'nilai_terendah' => 68.8],
            ];
        }
    }
