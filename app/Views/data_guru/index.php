<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<?php if (in_array($role, ['kepsek', 'tu', 'superadmin'])): ?>
<a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<?php endif ?>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php
    $stats = [
        ['icon' => 'fa-users',    'value' => count($guru),                                                                                    'label' => 'Total Guru',    'color' => 'from-purple-500 to-purple-600'],
        ['icon' => 'fa-user-check','value' => count(array_filter($guru, fn($g) => ($g['employment_status'] ?? '') === 'PNS')),                 'label' => 'Guru PNS',     'color' => 'from-blue-500 to-blue-600'],
        ['icon' => 'fa-certificate','value' => count(array_filter($guru, fn($g) => ($g['employment_status'] ?? '') === 'PPPK')),               'label' => 'Guru PPPK',    'color' => 'from-green-500 to-green-600'],
        ['icon' => 'fa-user-tie', 'value' => count(array_filter($guru, fn($g) => ($g['employment_status'] ?? '') === 'Honorer')),              'label' => 'Guru Honorer', 'color' => 'from-orange-500 to-orange-600'],
    ];
    foreach ($stats as $i => $s):
    ?>
    <div class="bg-gradient-to-br <?= $s['color'] ?> rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <i class="fas <?= $s['icon'] ?> text-3xl"></i>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $s['value'] ?></h3>
        <p class="text-white/80 text-sm font-medium"><?= $s['label'] ?></p>
    </div>
    <?php endforeach ?>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">

    <!-- Table Header -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Guru</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data guru sekolah</p>
            </div>
            <div class="flex items-center space-x-3">
                <?php if ($canEdit): ?>
                <a href="<?= base_url('guru/add') ?>" class="btn-primary flex items-center space-x-2 px-5 py-2.5 rounded-xl font-semibold text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Guru</span>
                </a>
                <?php endif ?>
                <button onclick="window.print()" class="flex items-center space-x-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
                    <i class="fas fa-print"></i>
                    <span>Cetak</span>
                </button>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari guru..."
                        class="pl-9 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-accent w-52 text-sm transition-colors">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full" id="guruTable">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));" class="text-white">
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">NIP</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">JK</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Pendidikan</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Kontak</th>
                    <?php if ($canEdit): ?>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (empty($guru)): ?>
                    <tr>
                        <td colspan="<?= $canEdit ? 9 : 8 ?>" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-users text-6xl mb-4 block opacity-30"></i>
                            <p class="text-lg font-semibold">Belum ada data guru</p>
                            <?php if ($canEdit): ?>
                            <p class="text-sm mt-1">Klik tombol "Tambah Guru" untuk menambahkan data</p>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $statusColors = [
                        'PNS'     => 'bg-green-100 text-green-800',
                        'PPPK'    => 'bg-blue-100 text-blue-800',
                        'Honorer' => 'bg-yellow-100 text-yellow-800',
                        'GTY'     => 'bg-purple-100 text-purple-800',
                        'GTT'     => 'bg-orange-100 text-orange-800',
                    ];
                    foreach ($guru as $index => $g):
                        $statusClass = $statusColors[$g['employment_status'] ?? ''] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                        data-url="<?= base_url('guru/detail/' . $g['id']) ?>">
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $index + 1 ?></td>
                        <td class="px-6 py-4">
                            <?php if (!empty($g['photo'])): ?>
                                <img src="<?= base_url('uploads/guru/' . $g['photo']) ?>"
                                     alt="<?= esc($g['full_name']) ?>"
                                     class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
                                    <?= strtoupper(substr($g['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif ?>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= esc($g['nip'] ?? '-') ?></td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900"><?= esc($g['full_name']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($g['email'] ?? '') ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                <?= ($g['gender'] ?? '') === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                                <?= ($g['gender'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900"><?= esc($g['education_major'] ?? '-') ?></p>
                            <p class="text-xs text-gray-400"><?= esc($g['last_education'] ?? '-') ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                                <?= esc($g['employment_status'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-phone text-gray-400 text-xs"></i>
                                <span><?= esc($g['phone'] ?? '-') ?></span>
                            </div>
                        </td>
                        <?php if ($canEdit): ?>
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('guru/edit/' . $g['id']) ?>"
                                    class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $g['id'] ?>, '<?= esc($g['full_name']) ?>')"
                                    class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-semibold text-gray-900"><?= count($guru) ?></span> data guru
        </p>
    </div>
</div>

<!-- Modal Hapus -->
<?php if ($canEdit): ?>
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data guru <strong id="deleteGuruName"></strong>?</p>
            <div class="flex space-x-4">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 font-semibold transition-all">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    // Row click → detail
    document.querySelectorAll('#guruTable tbody tr[data-url]').forEach(row => {
        row.addEventListener('click', () => window.location.href = row.dataset.url);
    });

    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr[data-url]').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    <?php if ($canEdit): ?>
    function confirmDelete(id, name) {
        document.getElementById('deleteGuruName').textContent = name;
        document.getElementById('deleteForm').action = '<?= base_url('guru/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    <?php endif ?>
</script>
<?php $this->endSection() ?>