<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php
$role = session()->get('role');
?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>

<?php if (in_array($role, ['kepsek', 'tu', 'superadmin'])): ?>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Keuangan</span>
</a>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<?php endif; ?>

<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<!-- Statistics Mini Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <?php
    $stats = [
        ['icon' => 'fa-users',     'value' => count($siswa),                                                            'label' => 'Total Siswa'],
        ['icon' => 'fa-mars',      'value' => count(array_filter($siswa, fn($s) => ($s['gender'] ?? '') === 'L')),      'label' => 'Laki-laki'],
        ['icon' => 'fa-venus',     'value' => count(array_filter($siswa, fn($s) => ($s['gender'] ?? '') === 'P')),      'label' => 'Perempuan'],
        ['icon' => 'fa-user-check','value' => count(array_filter($siswa, fn($s) => ($s['status'] ?? '') === 'aktif')), 'label' => 'Siswa Aktif'],
    ];
    $statColors = ['from-blue-500 to-blue-600', 'from-indigo-500 to-indigo-600', 'from-pink-500 to-pink-600', 'from-emerald-500 to-emerald-600'];
    foreach ($stats as $i => $stat):
    ?>
    <div class="bg-gradient-to-br <?= $statColors[$i] ?> rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
        <div class="flex items-center justify-between">
            <i class="fas <?= $stat['icon'] ?> text-4xl opacity-80"></i>
            <div class="text-right">
                <p class="text-3xl font-bold"><?= $stat['value'] ?></p>
                <p class="text-sm opacity-80"><?= $stat['label'] ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Action Bar -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6" data-aos="fade-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex items-center space-x-3">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('siswa/add') ?>" class="btn-primary flex items-center space-x-2 px-6 py-3 rounded-xl font-semibold shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Tambah Siswa</span>
            </a>
            <?php endif; ?>
            <button onclick="window.print()" class="flex items-center space-x-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all font-semibold">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari siswa..."
                    class="pl-10 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-accent w-64 transition-colors">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select id="filterJurusan" class="px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none">
                <option value="">Semua Jurusan</option>
            </select>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="overflow-x-auto">
        <table class="w-full" id="siswaTable">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));" class="text-white">
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">NISN</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Agama</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tgl Lahir</th>
                    <?php if ($canEdit): ?>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($siswa)): ?>
                    <tr>
                        <td colspan="<?= $canEdit ? 9 : 8 ?>" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-inbox text-6xl mb-4 block opacity-30"></i>
                            <p class="text-lg font-semibold">Belum ada data siswa</p>
                            <?php if ($canEdit): ?>
                            <p class="text-sm mt-1">Klik tombol "Tambah Siswa" untuk menambahkan data</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($siswa as $index => $s): ?>
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                        data-url="<?= base_url('siswa/detail/' . $s['id']) ?>"
                        data-jurusan="<?= esc($s['major_name'] ?? '') ?>">
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $index + 1 ?></td>
                        <td class="px-6 py-4">
                            <?php if (!empty($s['photo'])): ?>
                                <img src="<?= base_url('uploads/siswa/' . $s['photo']) ?>"
                                     alt="<?= esc($s['full_name']) ?>"
                                     class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                     style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
                                    <?= strtoupper(substr($s['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?= esc($s['nisn'] ?? '-') ?></td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900"><?= esc($s['full_name']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($s['nis'] ?? '') ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                <?= esc($s['grade']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <?= esc($s['major_name'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= esc($s['religion'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <?= !empty($s['birth_date']) ? date('d/m/Y', strtotime($s['birth_date'])) : '-' ?>
                        </td>
                        <?php if ($canEdit): ?>
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('siswa/edit/' . $s['id']) ?>"
                                    class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $s['id'] ?>, '<?= esc($s['full_name']) ?>')"
                                    class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data siswa <strong id="deleteStudentName"></strong>?</p>
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
<?php endif; ?>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script>
    // Row click → detail
    document.querySelectorAll('#siswaTable tbody tr[data-url]').forEach(row => {
        row.addEventListener('click', () => window.location.href = row.dataset.url);
    });

    // Populate dropdown jurusan
    const jurusanSet = new Set();
    document.querySelectorAll('#siswaTable tbody tr[data-jurusan]').forEach(row => {
        if (row.dataset.jurusan) jurusanSet.add(row.dataset.jurusan);
    });
    const select = document.getElementById('filterJurusan');
    jurusanSet.forEach(j => {
        const opt = document.createElement('option');
        opt.value = j; opt.textContent = j;
        select.appendChild(opt);
    });

    // Search & Filter
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('filterJurusan').addEventListener('change', filterTable);

    function filterTable() {
        const search  = document.getElementById('searchInput').value.toLowerCase();
        const jurusan = document.getElementById('filterJurusan').value;
        document.querySelectorAll('#siswaTable tbody tr[data-url]').forEach(row => {
            const matchS = row.textContent.toLowerCase().includes(search);
            const matchJ = jurusan === '' || row.dataset.jurusan === jurusan;
            row.style.display = matchS && matchJ ? '' : 'none';
        });
    }

    <?php if ($canEdit): ?>
    function confirmDelete(id, name) {
        document.getElementById('deleteStudentName').textContent = name;
        document.getElementById('deleteForm').action = '<?= base_url('siswa/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    <?php endif; ?>
</script>
<?php $this->endSection() ?>