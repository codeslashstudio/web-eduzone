<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('absensi') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-chart-pie w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard Absensi</span>
</a>
<a href="<?= base_url('absensi/harian') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-check w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi Harian</span>
</a>
<?php if (in_array(session()->get('role'), ['guru_mapel','tu','superadmin'])): ?>
<a href="<?= base_url('absensi/mapel') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-book-open w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi Mapel</span>
</a>
<?php endif ?>
<a href="<?= base_url('absensi/rekap') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-table w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Rekap Absensi</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$date      = $date      ?? date('Y-m-d');
$class_id  = $class_id  ?? '';
$siswa     = $siswa     ?? [];
$kelasList = $kelasList ?? [];
$kelasInfo = $kelasInfo ?? [];
$total     = count($siswa);
?>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<!-- FILTER -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-sm" style="color:var(--color-primary)"></i>
        Pilih Kelas & Tanggal
    </h3>
    <form method="GET" action="<?= base_url('absensi/harian') ?>" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal</label>
            <input type="date" name="date" value="<?= esc($date) ?>"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-400">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kelas</label>
            <select name="class_id"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:border-blue-400 min-w-44">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['class_id'] ?>"
                    <?= $class_id == $k['class_id'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kelas']) ?> (<?= $k['jumlah_siswa'] ?> siswa)
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <button type="submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-search text-xs"></i> Tampilkan
        </button>
    </form>
</div>

<?php if ($class_id && !empty($siswa)): ?>

<!-- Form absensi -->
<form method="POST" action="<?= base_url('absensi/harian/store') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="date"     value="<?= esc($date) ?>">
    <input type="hidden" name="class_id" value="<?= esc($class_id) ?>">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">

        <!-- Header -->
        <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-gray-900 text-lg">
                    Absensi — <?= esc($kelasInfo['nama_kelas'] ?? '') ?>
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    <?= date('l, d F Y', strtotime($date)) ?> · <?= $total ?> siswa
                </p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="setAllStatus('Hadir')"
                    class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold hover:bg-emerald-200 transition-all">
                    <i class="fas fa-check-double mr-1"></i>Semua Hadir
                </button>
                <button type="button" onclick="setAllStatus('Alpa')"
                    class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition-all">
                    <i class="fas fa-times mr-1"></i>Semua Alpa
                </button>
            </div>
        </div>

        <!-- Summary bar -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4 text-sm" id="summaryBar">
            <span class="flex items-center gap-1.5 font-semibold text-emerald-600">
                <i class="fas fa-check-circle text-xs"></i> Hadir: <span id="countHadir">0</span>
            </span>
            <span class="flex items-center gap-1.5 font-semibold text-blue-600">
                <i class="fas fa-file-medical text-xs"></i> Sakit: <span id="countSakit">0</span>
            </span>
            <span class="flex items-center gap-1.5 font-semibold text-yellow-600">
                <i class="fas fa-envelope text-xs"></i> Izin: <span id="countIzin">0</span>
            </span>
            <span class="flex items-center gap-1.5 font-semibold text-red-600">
                <i class="fas fa-times-circle text-xs"></i> Alpa: <span id="countAlpa">0</span>
            </span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:linear-gradient(135deg,var(--color-primary),var(--color-secondary))" class="text-white">
                        <th class="px-4 py-3 text-left w-8 font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Siswa</th>
                        <th class="px-4 py-3 text-center font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Jam Masuk</th>
                        <th class="px-4 py-3 text-center font-semibold">Jam Pulang</th>
                        <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($siswa as $i => $s): ?>
                    <tr class="hover:bg-gray-50 transition-colors" data-row>
                        <td class="px-4 py-3 text-gray-400 text-xs"><?= $i + 1 ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold shrink-0"
                                     style="background:var(--color-primary)">
                                    <?= strtoupper(substr($s['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?= esc($s['full_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= esc($s['nis'] ?? '-') ?></p>
                                </div>
                                <span class="text-xs px-1.5 py-0.5 rounded-full
                                    <?= $s['gender'] === 'P' ? 'bg-pink-100 text-pink-600' : 'bg-blue-100 text-blue-600' ?>">
                                    <?= $s['gender'] ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <select name="students[<?= $s['student_id'] ?>][status]"
                                class="status-select px-3 py-1.5 rounded-lg border-2 text-sm font-semibold focus:outline-none transition-all"
                                onchange="updateRowStyle(this); updateCount()">
                                <?php foreach (['Hadir','Sakit','Izin','Alpa'] as $st): ?>
                                <option value="<?= $st ?>" <?= ($s['status'] ?? 'Hadir') === $st ? 'selected' : '' ?>>
                                    <?= $st ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="time" name="students[<?= $s['student_id'] ?>][check_in]"
                                value="<?= esc(substr($s['check_in'] ?? '', 0, 5)) ?>"
                                class="px-2 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="time" name="students[<?= $s['student_id'] ?>][check_out]"
                                value="<?= esc(substr($s['check_out'] ?? '', 0, 5)) ?>"
                                class="px-2 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="students[<?= $s['student_id'] ?>][notes]"
                                value="<?= esc($s['notes'] ?? '') ?>"
                                placeholder="Keterangan..."
                                class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none">
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Total <strong class="text-gray-800"><?= $total ?></strong> siswa</p>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Absensi
            </button>
        </div>
    </div>
</form>

<?php elseif ($class_id && empty($siswa)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-14 text-center text-gray-400" data-aos="fade-up">
    <i class="fas fa-users-slash text-5xl mb-4 text-gray-200"></i>
    <p class="font-semibold">Tidak ada siswa aktif di kelas ini</p>
</div>

<?php else: ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-14 text-center text-gray-400" data-aos="fade-up">
    <i class="fas fa-clipboard-list text-5xl mb-4 text-gray-200"></i>
    <p class="font-semibold text-gray-600 mb-1">Pilih Kelas Terlebih Dahulu</p>
    <p class="text-sm">Pilih kelas dan tanggal di atas untuk memulai input absensi</p>
</div>
<?php endif ?>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
const statusColors = {
    'Hadir': 'border-emerald-400 bg-emerald-50 text-emerald-700',
    'Sakit': 'border-blue-400 bg-blue-50 text-blue-700',
    'Izin':  'border-yellow-400 bg-yellow-50 text-yellow-700',
    'Alpa':  'border-red-400 bg-red-50 text-red-700',
};

function updateRowStyle(select) {
    const val = select.value;
    const row = select.closest('tr');
    row.className = 'hover:bg-gray-50 transition-colors ';
    if (val === 'Alpa')  row.classList.add('bg-red-50/30');
    if (val === 'Sakit') row.classList.add('bg-blue-50/30');
    if (val === 'Izin')  row.classList.add('bg-yellow-50/30');
    select.className = 'status-select px-3 py-1.5 rounded-lg border-2 text-sm font-semibold focus:outline-none transition-all ' + (statusColors[val] || '');
}

function updateCount() {
    const c = {Hadir:0, Sakit:0, Izin:0, Alpa:0};
    document.querySelectorAll('.status-select').forEach(s => c[s.value] = (c[s.value]||0) + 1);
    document.getElementById('countHadir').textContent = c.Hadir;
    document.getElementById('countSakit').textContent = c.Sakit;
    document.getElementById('countIzin').textContent  = c.Izin;
    document.getElementById('countAlpa').textContent  = c.Alpa;
}

function setAllStatus(status) {
    document.querySelectorAll('.status-select').forEach(s => {
        s.value = status;
        updateRowStyle(s);
    });
    updateCount();
}

document.querySelectorAll('.status-select').forEach(s => updateRowStyle(s));
updateCount();
</script>
<?php $this->endSection() ?>