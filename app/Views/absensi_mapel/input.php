<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$jadwal    = $jadwal    ?? [];
$siswaList = $siswaList ?? [];
$sesi      = $sesi      ?? [];
$today     = $today     ?? date('Y-m-d');
$namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tglFormat = date('j', strtotime($today)) . ' ' . $namaBulan[(int)date('n', strtotime($today))] . ' ' . date('Y', strtotime($today));
$hadir  = count(array_filter($siswaList, fn($s) => $s['status'] === 'Hadir'));
$alpa   = count(array_filter($siswaList, fn($s) => $s['status'] === 'Alpa'));
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('absensi-mapel') ?>" class="hover:text-gray-600">Absensi Mapel</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= esc($jadwal['subject']) ?></span>
</div>

<!-- Info Jadwal -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:var(--color-primary)22">
                <i class="fas fa-chalkboard-teacher text-xl" style="color:var(--color-primary)"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg"><?= esc($jadwal['subject']) ?></h2>
                <div class="flex gap-2 flex-wrap mt-1">
                    <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-2 py-0.5 rounded-lg">
                        <?= esc($jadwal['nama_kelas'] ?? $jadwal['grade'].' '.$jadwal['major']) ?>
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">
                        <i class="fas fa-clock mr-1"></i><?= substr($jadwal['start_time'],0,5) ?> – <?= substr($jadwal['end_time'],0,5) ?>
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">
                        <i class="fas fa-calendar mr-1"></i><?= $tglFormat ?>
                    </span>
                    <?php if ($jadwal['room']): ?>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">
                        <i class="fas fa-door-open mr-1"></i><?= esc($jadwal['room']) ?>
                    </span>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <!-- Live counter -->
        <div class="flex gap-3">
            <div class="text-center bg-emerald-50 rounded-xl px-4 py-2">
                <p class="text-2xl font-extrabold text-emerald-600" id="countHadir"><?= $hadir ?></p>
                <p class="text-xs text-emerald-500 font-semibold">Hadir</p>
            </div>
            <div class="text-center bg-red-50 rounded-xl px-4 py-2">
                <p class="text-2xl font-extrabold text-red-500" id="countAlpa"><?= $alpa ?></p>
                <p class="text-xs text-red-400 font-semibold">Alpa</p>
            </div>
            <div class="text-center bg-gray-50 rounded-xl px-4 py-2">
                <p class="text-2xl font-extrabold text-gray-600"><?= count($siswaList) ?></p>
                <p class="text-xs text-gray-400 font-semibold">Total</p>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="<?= base_url('absensi-mapel/store/' . $jadwal['id']) ?>">
    <?= csrf_field() ?>

    <!-- Topik & Catatan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
        <h3 class="font-bold text-gray-700 mb-3 text-sm"><i class="fas fa-book-open mr-2 text-indigo-500"></i>Info Sesi Mengajar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Topik / Materi Hari Ini</label>
                <input type="text" name="topic" value="<?= esc($sesi['topic'] ?? '') ?>"
                       placeholder="Contoh: Persamaan Kuadrat — Pemfaktoran"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Catatan (opsional)</label>
                <input type="text" name="notes" value="<?= esc($sesi['notes'] ?? '') ?>"
                       placeholder="Catatan tambahan..."
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-400">
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800">Daftar Siswa</h3>
                <p class="text-xs text-gray-400 mt-0.5"><?= count($siswaList) ?> siswa · Klik nama untuk toggle status</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="setAll('Hadir')"
                        class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-check mr-1"></i>Semua Hadir
                </button>
                <button type="button" onclick="setAll('Alpa')"
                        class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                    <i class="fas fa-times mr-1"></i>Semua Alpa
                </button>
            </div>
        </div>

        <div class="divide-y divide-gray-50">
            <?php foreach ($siswaList as $i => $s): ?>
            <?php $isAlpa = $s['status'] === 'Alpa'; ?>
            <div class="siswa-row flex items-center gap-4 px-5 py-3 hover:bg-gray-50 cursor-pointer transition-colors <?= $isAlpa ? 'bg-red-50' : '' ?>"
                 onclick="toggleStatus(this)">
                <input type="hidden" name="student_id[]" value="<?= $s['id'] ?>">
                <input type="hidden" name="status[]" value="<?= $s['status'] ?>" class="status-input">

                <!-- Nomor -->
                <span class="text-xs text-gray-400 w-6 shrink-0"><?= $i+1 ?></span>

                <!-- Avatar -->
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0 <?= $isAlpa ? 'bg-red-400' : 'bg-indigo-400' ?> avatar-bg">
                    <?= strtoupper(substr($s['full_name'], 0, 1)) ?>
                </div>

                <!-- Nama -->
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($s['full_name']) ?></p>
                    <p class="text-xs text-gray-400"><?= esc($s['nis']) ?></p>
                </div>

                <!-- Gender badge -->
                <span class="text-xs px-2 py-0.5 rounded-lg <?= $s['gender']==='L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' ?>">
                    <?= $s['gender'] === 'L' ? 'L' : 'P' ?>
                </span>

                <!-- Status badge -->
                <span class="status-badge text-xs font-bold px-3 py-1.5 rounded-xl min-w-16 text-center
                    <?= $isAlpa ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' ?>">
                    <?= $s['status'] ?>
                </span>
            </div>
            <?php endforeach ?>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-between items-center">
            <a href="<?= base_url('absensi-mapel') ?>"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1 text-xs"></i> Kembali
            </a>
            <button type="submit"
                    class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Absensi
            </button>
        </div>
    </div>
</form>

<script>
function toggleStatus(row) {
    const input  = row.querySelector('.status-input');
    const badge  = row.querySelector('.status-badge');
    const avatar = row.querySelector('.avatar-bg');
    const isHadir = input.value === 'Hadir';

    input.value = isHadir ? 'Alpa' : 'Hadir';
    badge.textContent = isHadir ? 'Alpa' : 'Hadir';

    if (isHadir) {
        badge.className  = 'status-badge text-xs font-bold px-3 py-1.5 rounded-xl min-w-16 text-center bg-red-100 text-red-600';
        avatar.className = avatar.className.replace('bg-indigo-400','bg-red-400');
        row.classList.add('bg-red-50');
    } else {
        badge.className  = 'status-badge text-xs font-bold px-3 py-1.5 rounded-xl min-w-16 text-center bg-emerald-100 text-emerald-700';
        avatar.className = avatar.className.replace('bg-red-400','bg-indigo-400');
        row.classList.remove('bg-red-50');
    }
    updateCounter();
}

function setAll(status) {
    document.querySelectorAll('.siswa-row').forEach(row => {
        const input = row.querySelector('.status-input');
        if (input.value !== status) toggleStatus(row);
    });
}

function updateCounter() {
    const statuses = [...document.querySelectorAll('.status-input')].map(i => i.value);
    document.getElementById('countHadir').textContent = statuses.filter(s => s==='Hadir').length;
    document.getElementById('countAlpa').textContent  = statuses.filter(s => s==='Alpa').length;
}
</script>
<?php $this->endSection() ?>