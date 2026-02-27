<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuanganModel extends Model
{
    // ==========================================
    // PEMASUKAN
    // ==========================================

    public function getPemasukan($filters = [])
    {
        $builder = $this->db->table('transaksi_pemasukan tp');
        $builder->select('tp.*, kp.nama_kategori');
        $builder->join('kategori_pemasukan kp', 'tp.id_kategori = kp.id_kategori_pemasukan', 'left');

        if (!empty($filters['start_date'])) {
            $builder->where('tp.tanggal_transaksi >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tp.tanggal_transaksi <=', $filters['end_date']);
        }
        if (!empty($filters['kategori'])) {
            $builder->where('tp.id_kategori', $filters['kategori']);
        }
        if (!empty($filters['status'])) {
            $builder->where('tp.status', $filters['status']);
        }

        $builder->orderBy('tp.tanggal_transaksi', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPemasukanById($id)
    {
        $builder = $this->db->table('transaksi_pemasukan tp');
        $builder->select('tp.*, kp.nama_kategori');
        $builder->join('kategori_pemasukan kp', 'tp.id_kategori = kp.id_kategori_pemasukan', 'left');
        $builder->where('tp.id_pemasukan', $id);
        return $builder->get()->getRowArray();
    }

    public function insertPemasukan($data)
    {
        if (!isset($data['user_id']))    $data['user_id']    = 1;
        if (!isset($data['created_by'])) $data['created_by'] = 1;
        return $this->db->table('transaksi_pemasukan')->insert($data);
    }

    public function updatePemasukan($id, $data)
    {
        return $this->db->table('transaksi_pemasukan')
            ->where('id_pemasukan', $id)
            ->update($data);
    }

    public function deletePemasukan($id)
    {
        return $this->db->table('transaksi_pemasukan')
            ->where('id_pemasukan', $id)
            ->delete();
    }

    public function getTotalPemasukan($filters = [])
    {
        $builder = $this->db->table('transaksi_pemasukan');
        $builder->select('COALESCE(SUM(jumlah), 0) as total');
        $builder->where('status', 'Verified');

        if (!empty($filters['start_date'])) {
            $builder->where('tanggal_transaksi >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tanggal_transaksi <=', $filters['end_date']);
        }

        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    // ==========================================
    // PENGELUARAN
    // ==========================================

    public function getPengeluaran($filters = [])
    {
        $builder = $this->db->table('transaksi_pengeluaran tp');
        $builder->select('tp.*, kp.nama_kategori');
        $builder->join('kategori_pengeluaran kp', 'tp.id_kategori = kp.id_kategori_pengeluaran', 'left');

        if (!empty($filters['start_date'])) {
            $builder->where('tp.tanggal_transaksi >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tp.tanggal_transaksi <=', $filters['end_date']);
        }
        if (!empty($filters['kategori'])) {
            $builder->where('tp.id_kategori', $filters['kategori']);
        }
        if (!empty($filters['status'])) {
            $builder->where('tp.status', $filters['status']);
        }

        $builder->orderBy('tp.tanggal_transaksi', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPengeluaranById($id)
    {
        $builder = $this->db->table('transaksi_pengeluaran tp');
        $builder->select('tp.*, kp.nama_kategori');
        $builder->join('kategori_pengeluaran kp', 'tp.id_kategori = kp.id_kategori_pengeluaran', 'left');
        $builder->where('tp.id_pengeluaran', $id);
        return $builder->get()->getRowArray();
    }

    public function insertPengeluaran($data)
    {
        if (!isset($data['user_id']))    $data['user_id']    = 1;
        if (!isset($data['created_by'])) $data['created_by'] = 1;
        return $this->db->table('transaksi_pengeluaran')->insert($data);
    }

    public function updatePengeluaran($id, $data)
    {
        return $this->db->table('transaksi_pengeluaran')
            ->where('id_pengeluaran', $id)
            ->update($data);
    }

    public function deletePengeluaran($id)
    {
        return $this->db->table('transaksi_pengeluaran')
            ->where('id_pengeluaran', $id)
            ->delete();
    }

    public function getTotalPengeluaran($filters = [])
    {
        $builder = $this->db->table('transaksi_pengeluaran');
        $builder->select('COALESCE(SUM(jumlah), 0) as total');
        $builder->where('status', 'Paid');

        if (!empty($filters['start_date'])) {
            $builder->where('tanggal_transaksi >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tanggal_transaksi <=', $filters['end_date']);
        }

        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    // ==========================================
    // DANA BOS/BOP
    // ==========================================

    public function getDanaBOS($tahun_ajaran = null)
    {
        $builder = $this->db->table('dana_bos');
        if ($tahun_ajaran) {
            $builder->where('tahun_ajaran', $tahun_ajaran);
        }
        $builder->orderBy('tanggal_terima', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getDanaBOSById($id)
    {
        return $this->db->table('dana_bos')
            ->where('id_bos', $id)
            ->get()->getRowArray();
    }

    public function getRealisasiBOS($id_bos)
    {
        $builder = $this->db->table('realisasi_bos rb');
        $builder->select('rb.*, tp.keterangan, tp.jumlah as jumlah_pengeluaran, tp.tanggal_transaksi');
        $builder->join('transaksi_pengeluaran tp', 'rb.id_pengeluaran = tp.id_pengeluaran');
        $builder->where('rb.id_bos', $id_bos);
        return $builder->get()->getResultArray();
    }

    public function insertDanaBOS($data)
    {
        return $this->db->table('dana_bos')->insert($data);
    }

    // ==========================================
    // PENGAJUAN ANGGARAN
    // ==========================================

    public function getPengajuan($status = null)
    {
        $builder = $this->db->table('pengajuan_anggaran pa');
        $builder->select('pa.*, kp.nama_kategori, u.fullname as pengaju');
        $builder->join('kategori_pengeluaran kp', 'pa.kategori_pengeluaran = kp.id_kategori_pengeluaran', 'left');
        $builder->join('users u', 'pa.created_by = u.id', 'left');

        if ($status) {
            $builder->where('pa.status', $status);
        }

        $builder->orderBy('pa.tanggal_pengajuan', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function insertPengajuan($data)
    {
        return $this->db->table('pengajuan_anggaran')->insert($data);
    }

    public function updateStatusPengajuan($id, $status, $catatan = null, $reviewer_id = null)
    {
        $data = [
            'status'            => $status,
            'catatan_reviewer'  => $catatan,
            'reviewed_by'       => $reviewer_id,
            'reviewed_at'       => date('Y-m-d H:i:s'),
        ];

        return $this->db->table('pengajuan_anggaran')
            ->where('id_pengajuan', $id)
            ->update($data);
    }

    // ==========================================
    // DASHBOARD & STATISTIK
    // ==========================================

    public function getDashboardStats($filters = [])
    {
        $totalPemasukan   = $this->getTotalPemasukan($filters);
        $totalPengeluaran = $this->getTotalPengeluaran($filters);
        $saldo            = $totalPemasukan - $totalPengeluaran;

        // Dana BOS
        $builder = $this->db->table('dana_bos');
        if (!empty($filters['tahun_ajaran'])) {
            $builder->where('tahun_ajaran', $filters['tahun_ajaran']);
        }
        $builder->select('COALESCE(SUM(jumlah_diterima), 0) as total');
        $totalBos = $builder->get()->getRowArray()['total'] ?? 0;

        // Transaksi terbaru (gabung pemasukan + pengeluaran)
        $pBuilder = $this->db->table('transaksi_pemasukan tp');
        $pBuilder->select("tp.id_pemasukan as id, tp.tanggal_transaksi, tp.keterangan, tp.jumlah, 'pemasukan' as tipe");
        $pBuilder->orderBy('tp.tanggal_transaksi', 'DESC');
        $pBuilder->limit(5);
        $recentPemasukan = $pBuilder->get()->getResultArray();

        $eBuilder = $this->db->table('transaksi_pengeluaran tp');
        $eBuilder->select("tp.id_pengeluaran as id, tp.tanggal_transaksi, tp.keterangan, tp.jumlah, 'pengeluaran' as tipe");
        $eBuilder->orderBy('tp.tanggal_transaksi', 'DESC');
        $eBuilder->limit(5);
        $recentPengeluaran = $eBuilder->get()->getResultArray();

        $transaksiTerbaru = array_merge($recentPemasukan, $recentPengeluaran);
        usort($transaksiTerbaru, fn($a, $b) => strtotime($b['tanggal_transaksi']) - strtotime($a['tanggal_transaksi']));
        $transaksiTerbaru = array_slice($transaksiTerbaru, 0, 5);

        return [
            'total_pemasukan'   => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo'             => $saldo,
            'total_bos'         => $totalBos,
            'transaksi_terbaru' => $transaksiTerbaru,
            // alias lama (backward compat)
            'pemasukan'         => $totalPemasukan,
            'pengeluaran'       => $totalPengeluaran,
            'dana_bos'          => $totalBos,
        ];
    }

    public function getKategoriPengeluaranStats($filters = [])
    {
        $builder = $this->db->table('transaksi_pengeluaran tp');
        $builder->select('kp.nama_kategori, SUM(tp.jumlah) as total');
        $builder->join('kategori_pengeluaran kp', 'tp.id_kategori = kp.id_kategori_pengeluaran');
        $builder->where('tp.status', 'Paid');

        if (!empty($filters['start_date'])) {
            $builder->where('tp.tanggal_transaksi >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('tp.tanggal_transaksi <=', $filters['end_date']);
        }

        $builder->groupBy('kp.id_kategori_pengeluaran');
        return $builder->get()->getResultArray();
    }

    // ==========================================
    // AUDIT LOG
    // ==========================================

    public function logAudit($data)
    {
        return $this->db->table('audit_keuangan')->insert($data);
    }

    public function getAuditLog($filters = [])
    {
        $builder = $this->db->table('audit_keuangan al');
        $builder->select('al.*, u.fullname as user_name');
        $builder->join('users u', 'al.user_id = u.id', 'left');

        if (!empty($filters['tabel'])) {
            $builder->where('al.tabel', $filters['tabel']);
        }
        if (!empty($filters['start_date'])) {
            $builder->where('DATE(al.created_at) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(al.created_at) <=', $filters['end_date']);
        }

        $builder->orderBy('al.created_at', 'DESC');
        $builder->limit(100);
        return $builder->get()->getResultArray();
    }

    public function getAuditLogById($id)
    {
        $builder = $this->db->table('audit_keuangan al');
        $builder->select('al.*, u.fullname as user_name');
        $builder->join('users u', 'al.user_id = u.id', 'left');
        $builder->where('al.id', $id);
        return $builder->get()->getRowArray();
    }

    // ==========================================
    // KATEGORI
    // ==========================================

    public function getKategoriPemasukan()
    {
        return $this->db->table('kategori_pemasukan')
            ->where('is_active', 1)
            ->get()->getResultArray();
    }

    public function getKategoriPengeluaran()
    {
        return $this->db->table('kategori_pengeluaran')
            ->where('is_active', 1)
            ->get()->getResultArray();
    }

    // ==========================================
    // UTILITY
    // ==========================================

    public function generateNoTransaksi($jenis = 'IN')
    {
        $prefix = $jenis === 'IN' ? 'TRX-IN-' : 'TRX-OUT-';
        $table  = $jenis === 'IN' ? 'transaksi_pemasukan' : 'transaksi_pengeluaran';
        $pkCol  = $jenis === 'IN' ? 'id_pemasukan' : 'id_pengeluaran';

        $builder = $this->db->table($table);
        $builder->select('no_transaksi');
        $builder->like('no_transaksi', $prefix, 'after');
        $builder->orderBy($pkCol, 'DESC');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();

        $newNo = $result
            ? (int) str_replace($prefix, '', $result['no_transaksi']) + 1
            : 1;

        return $prefix . str_pad($newNo, 6, '0', STR_PAD_LEFT);
    }
}