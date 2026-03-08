<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<a href="<?= base_url('absensi/mapel?date=' . ($date ?? date('Y-m-d'))) ?>" class="menu-item active flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-book-open w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Absensi Per Mapel</span>
</a>
<a href="<?= base_url('absensi') ?>" class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl">
    <i class="fas fa-arrow-left w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Kembali</span>
</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<?php
$schedule    = $schedule    ?? [];
$siswa       = $siswa       ?? [];
$date        = $date        ?? date('Y-m-d');
$schedule_id = $schedule_id ?? '';
$teaching_id = $teaching_id ?? '';
$teacher_id  = $teacher_id  ?? '';
$total       = count($siswa);
$statusList  = ['Hadir', 'Sakit', 'Izin', 'Alpa'];
$statusColor = ['Hadir' => 'emerald', 'Sakit' => 'blue', 'Izin' => 'yellow', 'Alpa' => 'red'];
?>

<!-- INFO SESI -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5 flex flex-wrap gap-4 items-center" data-aos="fade-up">
    <div class="stat-icon-bg w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-book-open"></i>
    </div>
    <div class="flex-1">
        <h2 class="font-bold text-gray-900 text-lg"><?= esc($schedule['subject'] ?? '-') ?></h2>
        <p class="text-xs text-gray-400 mt-0.5 flex flex-wrap gap-3">
            <span><i class="fas fa-clock mr-1"></i><?= substr($schedule['start_time'] ?? '', 0, 5) ?> – <?= substr($schedule['end_time'] ?? '', 0, 5) ?></span>
            <span><i class="fas fa-door-open mr-1"></i><?= esc($schedule['nama_kelas'] ?? '-') ?></span>
            <span><i class="fas fa-chalkboard-teacher mr-1"></i><?= esc($schedule['nama_guru'] ?? '-') ?></span>
            <span><i class="fas fa-calendar mr-1"></i><?= date('d F Y', strtotime($date)) ?></span>
        </p>
    </div>
    <div class="text-center">
        <p class="text-2xl font-bold text-gray-900"><?= $total ?></p>
        <p class="text-xs text-gray-400">Siswa</p>
    </div>
</div>

<!-- FLASH -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<?php if (empty($siswa)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center text-gray-400" data-aos="fade-up">
    <i class="fas fa-users-slash text-5xl mb-4 text-gray-200"></i>
    <p>Tidak ada siswa di kelas ini</p>
</div>
<?php else: ?>

<form method="POST" action="<?= base_url('absensi/mapel/store') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="date"        value="<?= esc($date) ?>">
    <input type="hidden" name="schedule_id" value="<?= esc($schedule_id) ?>">
    <input type="hidden" name="teaching_id" value="<?= esc($teaching_id) ?>">
    <input type="hidden" name="teacher_id"  value="<?= esc($teacher_id) ?>">

    <!-- Topik -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4" data-aos="fade-up">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            <i class="fas fa-pen-nib mr-1.5 text-gray-400"></i>Topik / Materi Hari Ini
        </label>
        <input type="text" name="topic"
               placeholder="Contoh: Persamaan Kuadrat, Fotosintesis, Teks Argumentasi..."
               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
    </div>

    <!-- Aksi massal -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-wrap gap-2 items-center" data-aos="fade-up">
        <span class="text-xs font-semibold text-gray-500 mr-2">Set semua:</span>
        <?php foreach ($statusList as $s): ?>
        <?php $c = $statusColor[$s]; ?>
        <button type="button" onclick="setAllStatus('<?= $s ?>')"
            class="px-3 py-1.5 text-xs font-bold rounded-lg border transition-all
                   border-<?= $c ?>-200 text-<?= $c ?>-600 bg-<?= $c ?>-50 hover:bg-<?= $c ?>-100">
            Semua <?= $s ?>
        </button>
        <?php endforeach ?>
        <span class="ml-auto text-xs text-gray-400" id="countLabel">0 / <?= $total ?> diisi</span>
    </div>

    <!-- TABEL SISWA -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-users text-gray-400 text-sm"></i>
            <span class="font-bold text-gray-900 text-sm">Daftar Siswa</span>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= $total ?> siswa</span>
        </div>

        <div class="divide-y divide-gray-50">
            <?php foreach ($siswa as $idx => $s): ?>
            <?php $currentStatus = $s['status'] ?? 'Hadir'; ?>
            <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-gray-50 transition-colors siswa-row"
                 data-status="<?= $currentStatus ?>">
                <!-- No urut + nama -->
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 bg-gray-100 text-gray-500">
                    <?= $idx + 1 ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($s['full_name']) ?></p>
                    <p class="text-xs text-gray-400"><?= esc($s['nis'] ?? '-') ?> · <?= $s['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                </div>
                <!-- Status toggle -->
                <div class="flex gap-1.5 shrink-0">
                    <?php foreach ($statusList as $st): ?>
                    <?php $c = $statusColor[$st]; ?>
                    <label class="status-label cursor-pointer">
                        <input type="radio" name="students[<?= $s['student_id'] ?>][status]"
                               value="<?= $st ?>"
                               <?= $currentStatus === $st ? 'checked' : '' ?>
                               class="hidden status-radio"
                               onchange="updateCount()">
                        <span class="status-btn inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold border transition-all
                                     border-<?= $c ?>-200 text-<?= $c ?>-500
                                     <?= $currentStatus === $st ? 'bg-'.$c.'-100 ring-1 ring-'.$c.'-400' : 'bg-white hover:bg-'.$c.'-50' ?>">
                            <?= $st ?>
                        </span>
                    </label>
                    <?php endforeach ?>
                </div>
                <!-- Catatan -->
                <input type="text" name="students[<?= $s['student_id'] ?>][notes]"
                       value="<?= esc($s['notes'] ?? '') ?>"
                       placeholder="Catatan..."
                       class="w-28 text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-blue-400 transition-all hidden sm:block">
            </div>
            <?php endforeach ?>
        </div>

        <!-- Footer submit -->
        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="<?= base_url('absensi/mapel?date=' . $date) ?>"
               class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Absensi
            </button>
        </div>
    </div>
</form>
<?php endif ?>

<script>
// Set semua status sekaligus
function setAllStatus(status) {
    document.querySelectorAll('.siswa-row').forEach(row => {
        const radio = row.querySelector(`input[value="${status}"]`);
        if (radio) {
            radio.checked = true;
            updateRowStyle(row, status);
        }
    });
    updateCount();
}

// Update style tombol status aktif
document.querySelectorAll('.status-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const row = this.closest('.siswa-row');
        updateRowStyle(row, this.value);
        updateCount();
    });
});

function updateRowStyle(row, status) {
    row.querySelectorAll('.status-btn').forEach(btn => {
        const label = btn.closest('label');
        const radio = label.querySelector('input');
        const colorMap = {Hadir:'emerald', Sakit:'blue', Izin:'yellow', Alpa:'red'};
        const c = colorMap[radio.value] || 'gray';
        // Reset semua
        btn.className = btn.className
            .replace(/bg-\w+-100/g, 'bg-white')
            .replace(/ring-1\s*ring-\w+-\d+/g, '');
        if (radio.value === status) {
            btn.classList.remove('bg-white');
            btn.classList.add(`bg-${c}-100`, 'ring-1', `ring-${c}-400`);
        }
    });
}

function updateCount() {
    const total   = <?= $total ?>;
    const checked = document.querySelectorAll('.status-radio:checked').length;
    document.getElementById('countLabel').textContent = `${checked} / ${total} diisi`;
}

updateCount();
</script>

<?php $this->endSection() ?>