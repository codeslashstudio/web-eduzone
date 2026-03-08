<?php

namespace App\Controllers;

class Inventaris extends BaseController
{
    private function db() { return \Config\Database::connect(); }

    private array $editRoles = ['superadmin', 'toolman', 'tu'];
    private array $viewRoles = ['superadmin', 'toolman', 'tu', 'kepsek'];

    private function authCheck(bool $requireEdit = false): void
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth/login'))->send(); exit;
        }
        $role = session()->get('role');
        if (!in_array($role, $this->viewRoles)) {
            redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak')->send(); exit;
        }
        if ($requireEdit && !in_array($role, $this->editRoles)) {
            redirect()->to(base_url('inventaris'))->with('error', 'Akses ditolak')->send(); exit;
        }
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $this->authCheck();
        $db = $this->db();

        $search    = $this->request->getGet('search')    ?? '';
        $condition = $this->request->getGet('condition') ?? '';
        $location  = $this->request->getGet('location')  ?? '';

        $sql    = "SELECT * FROM inventory WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND item_name LIKE ?";
            $params[] = "%$search%";
        }
        if ($condition) {
            $sql .= " AND `condition` = ?";
            $params[] = $condition;
        }
        if ($location) {
            $sql .= " AND location LIKE ?";
            $params[] = "%$location%";
        }

        $sql .= " ORDER BY item_name ASC";
        $list = $db->query($sql, $params)->getResultArray();

        // Stats
        $stats = $db->query("
            SELECT
                COUNT(*) total,
                SUM(quantity) total_unit,
                SUM(CASE WHEN `condition`='Baik' THEN 1 ELSE 0 END) baik,
                SUM(CASE WHEN `condition`='Rusak Ringan' THEN 1 ELSE 0 END) rusak_ringan,
                SUM(CASE WHEN `condition`='Rusak Berat' THEN 1 ELSE 0 END) rusak_berat
            FROM inventory
        ")->getRowArray();

        // Lokasi unik untuk filter
        $locations = $db->query("SELECT DISTINCT location FROM inventory WHERE location IS NOT NULL AND location != '' ORDER BY location")->getResultArray();

        return view('inventaris/index', [
            'title'     => 'Inventaris Barang',
            'list'      => $list,
            'stats'     => $stats,
            'locations' => $locations,
            'search'    => $search,
            'condition' => $condition,
            'location'  => $location,
            'canEdit'   => in_array(session()->get('role'), $this->editRoles),
        ]);
    }

    // ==============================
    // ADD
    // ==============================
    public function add()
    {
        $this->authCheck(true);
        return view('inventaris/form', [
            'title' => 'Tambah Barang',
            'mode'  => 'add',
            'item'  => [],
        ]);
    }

    // ==============================
    // STORE
    // ==============================
    public function store()
    {
        $this->authCheck(true);
        $this->db()->table('inventory')->insert([
            'item_name'  => $this->request->getPost('item_name'),
            'quantity'   => $this->request->getPost('quantity') ?? 1,
            'condition'  => $this->request->getPost('condition'),
            'location'   => $this->request->getPost('location'),
            'description' => $this->request->getPost('notes'),
        ]);
        return redirect()->to(base_url('inventaris'))->with('success', 'Barang berhasil ditambahkan');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $this->authCheck(true);
        $item = $this->db()->query("SELECT * FROM inventory WHERE id = ?", [$id])->getRowArray();
        if (!$item) return redirect()->to(base_url('inventaris'))->with('error', 'Barang tidak ditemukan');

        return view('inventaris/form', [
            'title' => 'Edit Barang',
            'mode'  => 'edit',
            'item'  => $item,
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update($id)
    {
        $this->authCheck(true);
        $this->db()->table('inventory')->update([
            'item_name' => $this->request->getPost('item_name'),
            'quantity'  => $this->request->getPost('quantity'),
            'condition' => $this->request->getPost('condition'),
            'location'  => $this->request->getPost('location'),
            'description' => $this->request->getPost('notes'),
        ], ['id' => $id]);
        return redirect()->to(base_url('inventaris'))->with('success', 'Barang berhasil diperbarui');
    }

    // ==============================
    // DELETE
    // ==============================
    public function delete($id)
    {
        $this->authCheck(true);
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('inventaris'));
        $this->db()->table('inventory')->delete(['id' => $id]);
        return redirect()->to(base_url('inventaris'))->with('success', 'Barang berhasil dihapus');
    }
}