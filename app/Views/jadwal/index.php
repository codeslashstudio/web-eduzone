<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('jadwal') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-calendar-alt w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Jadwal Pelajaran</span>
</a>
<?php if ($canEdit ?? false): ?>
<a href="<?= base_url('jadwal/add') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-plus-circle w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Tambah Jadwal</span>
</a>
<?php endif ?>
<a href="<?= base_url('dashboard') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$jadwal        = $jadwal        ?? [];
$jadwalPerHari = $jadwalPerHari ?? [];
$kelasList     = $kelasList     ?? [];
$guruList      = $guruList      ?? [];
$canEdit       = $canEdit       ?? false;
$hariIni       = $hariIni       ?? 'Senin';
$filterClassId   = $filterClassId   ?? '';
$filterTeacherId = $filterTeacherId ?? '';
$filterDay       = $filterDay       ?? '';
$totalJadwal   = count($jadwal);
$totalMapel    = count(array_unique(array_column($jadwal, 'subject')));
$hariUrut      = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$activeTab     = $filterDay ?: $hariIni;
?>

<!-- STATS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-border-accent" data-aos="fade-up">
        <div class="stat-icon-bg w-11 h-11 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-calendar-check text-lg"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $totalJadwal ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Total Sesi Jadwal</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="100">
        <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-book text-purple-600 text-lg"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $totalMapel ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Mata Pelajaran</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="200">
        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-chalkboard-teacher text-blue-600 text-lg"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= count($guruList) ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Guru Aktif</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="300">
        <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-door-open text-emerald-600 text-lg"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= count($kelasList) ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Total Kelas</p>
    </div>
</div>

<!-- FILTER BAR -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5" data-aos="fade-up">
    <form method="GET" action="<?= base_url('jadwal') ?>" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[160px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Filter Kelas</label>
            <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white focus:outline-none">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['class_id'] ?>" <?= $filterClassId == $k['class_id'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kelas']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="flex-1 min-w-[160px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Filter Guru</label>
            <select name="teacher_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white focus:outline-none">
                <option value="">Semua Guru</option>
                <?php foreach ($guruList as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $filterTeacherId == $g['id'] ? 'selected' : '' ?>>
                    <?= esc($g['full_name']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="flex-1 min-w-[140px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Filter Hari</label>
            <select name="day" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white focus:outline-none">
                <option value="">Semua Hari</option>
                <?php foreach ($hariUrut as $h): ?>
                <option value="<?= $h ?>" <?= $filterDay === $h ? 'selected' : '' ?>><?= $h ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-filter text-xs"></i> Filter
            </button>
            <a href="<?= base_url('jadwal') ?>" class="px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <i class="fas fa-times text-xs"></i> Reset
            </a>
        </div>
        <?php if ($canEdit): ?>
        <a href="<?= base_url('jadwal/add') ?>" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-emerald-500 text-white hover:bg-emerald-600 flex items-center gap-2 ml-auto">
            <i class="fas fa-plus text-xs"></i> Tambah Jadwal
        </a>
        <?php endif ?>
    </form>
</div>

<!-- TAB HARI -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <!-- Tab headers -->
    <div class="border-b border-gray-100 px-5 pt-4 flex gap-1 overflow-x-auto">
        <?php foreach ($hariUrut as $h): ?>
        <?php $count = count(array_filter($jadwal, fn($j) => $j['day'] === $h)); ?>
        <button onclick="switchTab('<?= $h ?>')" id="tab-<?= $h ?>"
            class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl whitespace-nowrap transition-all
                   <?= $h === $activeTab ? 'tab-active' : 'text-gray-500 hover:text-gray-700' ?>">
            <?= $h ?>
            <?php if ($count > 0): ?>
            <span class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full
                         <?= $h === $activeTab ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-500' ?>">
                <?= $count ?>
            </span>
            <?php endif ?>
        </button>
        <?php endforeach ?>
        <?php if (!$filterDay): ?>
        <button onclick="switchTab('semua')" id="tab-semua"
            class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl whitespace-nowrap transition-all text-gray-500 hover:text-gray-700">
            Semua
        </button>
        <?php endif ?>
    </div>

    <!-- Tab content per hari -->
    <?php foreach ($hariUrut as $h): ?>
    <div id="content-<?= $h ?>" class="tab-content <?= $h !== $activeTab ? 'hidden' : '' ?> p-5">
        <?php $sesiHari = array_values(array_filter($jadwal, fn($j) => $j['day'] === $h)); ?>
        <?php if (empty($sesiHari)): ?>
        <div class="py-10 text-center text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-200"></i>
            <p class="text-sm">Tidak ada jadwal hari <?= $h ?></p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Waktu</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Mata Pelajaran</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kelas</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Guru</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Ruang</th>
                        <?php if ($canEdit): ?>
                        <th class="py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Aksi</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($sesiHari as $j): ?>
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="py-3.5 px-3">
                            <span class="font-mono text-xs font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-lg">
                                <?= substr($j['start_time'], 0, 5) ?> – <?= substr($j['end_time'], 0, 5) ?>
                            </span>
                        </td>
                        <td class="py-3.5 px-3">
                            <span class="font-semibold text-gray-800"><?= esc($j['subject']) ?></span>
                        </td>
                        <td class="py-3.5 px-3">
                            <a href="<?= base_url('jadwal/kelas/' . ($j['class_id'] ?? '')) ?>"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg"
                               style="background:rgba(var(--color-primary-rgb),0.1);color:var(--color-primary)">
                                <?= esc($j['nama_kelas'] ?? $j['grade'].' '.$j['major'].' '.$j['class_group']) ?>
                            </a>
                        </td>
                        <td class="py-3.5 px-3">
                            <?php if ($j['teacher_id']): ?>
                            <a href="<?= base_url('jadwal/guru/' . $j['teacher_id']) ?>"
                               class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                                <?= esc($j['nama_guru'] ?? '-') ?>
                            </a>
                            <?php else: ?>
                            <span class="text-gray-400 text-xs">-</span>
                            <?php endif ?>
                        </td>
                        <td class="py-3.5 px-3 text-gray-500 text-xs">
                            <?= $j['room'] ? esc($j['room']) : '<span class="text-gray-300">-</span>' ?>
                        </td>
                        <?php if ($canEdit): ?>
                        <td class="py-3.5 px-3 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('jadwal/edit/' . $j['id']) ?>"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-pencil text-xs"></i>
                                </a>
                                <form method="POST" action="<?= base_url('jadwal/delete/' . $j['id']) ?>"
                                      onsubmit="return confirm('Hapus jadwal ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>
    </div>
    <?php endforeach ?>

    <!-- Tab content semua -->
    <div id="content-semua" class="tab-content hidden p-5">
        <?php if (empty($jadwal)): ?>
        <div class="py-10 text-center text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-200"></i>
            <p class="text-sm">Belum ada jadwal</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Hari</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Waktu</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Mata Pelajaran</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kelas</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Guru</th>
                        <?php if ($canEdit): ?>
                        <th class="py-3 px-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($jadwal as $j): ?>
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="py-3 px-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-lg
                                <?= $j['day'] === $hariIni ? 'text-white' : 'text-gray-500 bg-gray-100' ?>"
                                <?= $j['day'] === $hariIni ? 'style="background:var(--color-primary)"' : '' ?>>
                                <?= $j['day'] ?>
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="font-mono text-xs font-semibold text-gray-600">
                                <?= substr($j['start_time'], 0, 5) ?>–<?= substr($j['end_time'], 0, 5) ?>
                            </span>
                        </td>
                        <td class="py-3 px-3 font-semibold text-gray-800"><?= esc($j['subject']) ?></td>
                        <td class="py-3 px-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-lg"
                                  style="background:rgba(var(--color-primary-rgb),0.1);color:var(--color-primary)">
                                <?= esc($j['nama_kelas'] ?? $j['grade'].' '.$j['major'].' '.$j['class_group']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-3 text-gray-600"><?= esc($j['nama_guru'] ?? '-') ?></td>
                        <?php if ($canEdit): ?>
                        <td class="py-3 px-3 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('jadwal/edit/' . $j['id']) ?>"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="fas fa-pencil text-xs"></i>
                                </a>
                                <form method="POST" action="<?= base_url('jadwal/delete/' . $j['id']) ?>"
                                      onsubmit="return confirm('Hapus jadwal ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>
    </div>
</div>

<style>
.tab-active {
    background: var(--color-primary);
    color: white;
}
</style>

<script>
function switchTab(hari) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('tab-active');
        el.classList.add('text-gray-500');
        // reset badge color
        const badge = el.querySelector('span');
        if (badge) { badge.classList.remove('bg-white/30','text-white'); badge.classList.add('bg-gray-100','text-gray-500'); }
    });
    const content = document.getElementById('content-' + hari);
    const tab     = document.getElementById('tab-' + hari);
    if (content) content.classList.remove('hidden');
    if (tab) {
        tab.classList.add('tab-active');
        tab.classList.remove('text-gray-500');
        const badge = tab.querySelector('span');
        if (badge) { badge.classList.add('bg-white/30','text-white'); badge.classList.remove('bg-gray-100','text-gray-500'); }
    }
}
</script>

<?php $this->endSection() ?>