<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('jadwal') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$kelas         = $kelas         ?? [];
$jadwal        = $jadwal        ?? [];
$jadwalPerHari = $jadwalPerHari ?? [];
$totalMapel    = $totalMapel    ?? 0;
$hariIni       = $hariIni       ?? 'Senin';
$hariUrut      = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
?>

<!-- HEADER KELAS -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" data-aos="fade-up">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0"
                 style="background:var(--color-primary)">
                <?= esc($kelas['grade'] ?? '?') ?>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900"><?= esc($kelas['nama_kelas'] ?? '-') ?></h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    <?= esc($kelas['major_name'] ?? '') ?>
                    <?php if ($kelas['nama_wakel'] ?? ''): ?>
                    · Wali Kelas: <span style="color:var(--color-primary)"><?= esc($kelas['nama_wakel']) ?></span>
                    <?php endif ?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-center bg-gray-50 rounded-xl px-4 py-2.5">
                <p class="text-lg font-bold text-gray-900"><?= count($jadwal) ?></p>
                <p class="text-xs text-gray-400">Sesi / Minggu</p>
            </div>
            <div class="text-center bg-gray-50 rounded-xl px-4 py-2.5">
                <p class="text-lg font-bold text-gray-900"><?= $totalMapel ?></p>
                <p class="text-xs text-gray-400">Mata Pelajaran</p>
            </div>
            <a href="<?= base_url('jadwal/cetak/' . ($kelas['id'] ?? '')) ?>"
               target="_blank"
               class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold btn-primary rounded-xl">
                <i class="fas fa-print text-xs"></i> Cetak
            </a>
        </div>
    </div>
</div>

<!-- JADWAL TABLE PER HARI -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">

    <!-- Hari tabs (pill style) -->
    <div class="p-4 border-b border-gray-100 flex gap-2 overflow-x-auto">
        <?php foreach ($hariUrut as $h):
            $count = count($jadwalPerHari[$h] ?? []);
        ?>
        <button onclick="showHari('<?= $h ?>')" id="pill-<?= $h ?>"
            class="hari-pill px-4 py-2 text-xs font-bold rounded-xl whitespace-nowrap transition-all
                   <?= $h === $hariIni ? 'pill-active' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>">
            <?= $h ?> <?= $count > 0 ? "($count)" : '' ?>
        </button>
        <?php endforeach ?>
    </div>

    <!-- Content per hari -->
    <?php foreach ($hariUrut as $h): ?>
    <div id="hari-<?= $h ?>" class="hari-content <?= $h !== $hariIni ? 'hidden' : '' ?>">
        <?php $sesi = $jadwalPerHari[$h] ?? []; ?>
        <?php if (empty($sesi)): ?>
        <div class="py-12 text-center text-gray-400">
            <i class="fas fa-moon text-3xl text-gray-200 mb-3"></i>
            <p class="text-sm">Tidak ada pelajaran hari <?= $h ?></p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php foreach ($sesi as $idx => $j): ?>
            <div class="flex items-stretch hover:bg-gray-50 transition-colors">
                <!-- Nomor urut + waktu -->
                <div class="w-24 sm:w-32 flex flex-col items-center justify-center py-4 shrink-0 border-r border-gray-100">
                    <span class="text-xs font-mono font-semibold text-gray-800"><?= substr($j['start_time'], 0, 5) ?></span>
                    <div class="w-px h-3 bg-gray-300 my-1"></div>
                    <span class="text-xs font-mono text-gray-400"><?= substr($j['end_time'], 0, 5) ?></span>
                </div>
                <!-- Konten -->
                <div class="flex-1 px-5 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-gray-800"><?= esc($j['subject']) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-gray-300"></i>
                            <?= esc($j['nama_guru'] ?? 'Guru belum ditentukan') ?>
                            <?php if ($j['room'] ?? ''): ?>
                            <span class="text-gray-300">·</span>
                            <i class="fas fa-door-open text-gray-300"></i>
                            <?= esc($j['room']) ?>
                            <?php endif ?>
                        </p>
                    </div>
                    <?php
                    $start  = strtotime($j['start_time']);
                    $end    = strtotime($j['end_time']);
                    $durasi = round(($end - $start) / 60);
                    ?>
                    <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg shrink-0">
                        <?= $durasi ?> mnt
                    </span>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
    <?php endforeach ?>
</div>

<style>
.pill-active { background: var(--color-primary); color: white; }
</style>

<script>
function showHari(hari) {
    document.querySelectorAll('.hari-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.hari-pill').forEach(el => {
        el.classList.remove('pill-active');
        el.classList.add('bg-gray-100','text-gray-500');
    });
    document.getElementById('hari-' + hari)?.classList.remove('hidden');
    const pill = document.getElementById('pill-' + hari);
    if (pill) {
        pill.classList.add('pill-active');
        pill.classList.remove('bg-gray-100','text-gray-500');
    }
}
</script>

<?php $this->endSection() ?>