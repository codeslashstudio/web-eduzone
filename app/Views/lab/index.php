<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list       = $list       ?? [];
$stats      = $stats      ?? [];
$labList    = $labList    ?? [];
$canBook    = $canBook    ?? false;
$canApprove = $canApprove ?? false;
$status     = $status     ?? '';
$bulan      = $bulan      ?? date('m');
$tahun      = $tahun      ?? date('Y');
$lab        = $lab        ?? '';

$namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$statusColors = [
    'Menunggu'  => 'bg-yellow-100 text-yellow-700',
    'Disetujui' => 'bg-emerald-100 text-emerald-700',
    'Ditolak'   => 'bg-red-100 text-red-700',
];
$statusIcons = [
    'Menunggu'  => 'fa-clock',
    'Disetujui' => 'fa-check-circle',
    'Ditolak'   => 'fa-times-circle',
];
?>

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

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <?php foreach ([
        ['Total Booking',  $stats['total']     ?? 0, 'fa-flask',        '#6366f1'],
        ['Menunggu',       $stats['menunggu']  ?? 0, 'fa-clock',        '#f59e0b'],
        ['Disetujui',      $stats['disetujui'] ?? 0, 'fa-check-circle', '#10b981'],
        ['Ditolak',        $stats['ditolak']   ?? 0, 'fa-times-circle', '#ef4444'],
    ] as [$lbl,$val,$icon,$color]): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3"
             style="background:<?= $color ?>22">
            <i class="fas <?= $icon ?> text-base" style="color:<?= $color ?>"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $val ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?= $lbl ?></p>
    </div>
    <?php endforeach ?>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-flask" style="color:var(--color-primary)"></i>
            Peminjaman Laboratorium
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
        </h3>
        <?php if ($canBook): ?>
        <a href="<?= base_url('lab/add') ?>"
           class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Ajukan Peminjaman
        </a>
        <?php endif ?>
    </div>

    <!-- Filter -->
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu','Disetujui','Ditolak'] as $s): ?>
                <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach ?>
            </select>
            <select name="lab" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Lab</option>
                <?php foreach ($labList as $l): ?>
                <option value="<?= esc($l['lab_name']) ?>" <?= $lab === $l['lab_name'] ? 'selected' : '' ?>>
                    <?= esc($l['lab_name']) ?>
                </option>
                <?php endforeach ?>
            </select>
            <select name="bulan" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= (int)$bulan === $m ? 'selected' : '' ?>><?= $namaBulan[$m] ?></option>
                <?php endfor ?>
            </select>
            <select name="tahun" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor ?>
            </select>
            <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="<?= base_url('lab') ?>" class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-xl hover:bg-gray-50">
                <i class="fas fa-times text-xs"></i>
            </a>
        </form>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-flask text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada peminjaman lab</p>
    </div>
    <?php else: ?>
    <div class="divide-y divide-gray-50">
        <?php foreach ($list as $b): ?>
        <div class="p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                         style="background:var(--color-primary)22">
                        <i class="fas fa-flask text-sm" style="color:var(--color-primary)"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <p class="font-bold text-gray-900 text-sm"><?= esc($b['lab_name']) ?></p>
                            <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-lg <?= $statusColors[$b['status']] ?? 'bg-gray-100 text-gray-600' ?>">
                                <i class="fas <?= $statusIcons[$b['status']] ?? 'fa-circle' ?> text-xs"></i>
                                <?= $b['status'] ?>
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-1">
                            <span><i class="fas fa-calendar mr-1 text-gray-400"></i><?= date('d F Y', strtotime($b['date'])) ?></span>
                            <span><i class="fas fa-clock mr-1 text-gray-400"></i><?= substr($b['start_time'],0,5) ?> – <?= substr($b['end_time'],0,5) ?></span>
                            <?php if ($b['guru_name']): ?>
                            <span><i class="fas fa-chalkboard-teacher mr-1 text-gray-400"></i><?= esc($b['guru_name']) ?></span>
                            <?php endif ?>
                        </div>
                        <?php if ($b['purpose']): ?>
                        <p class="text-xs text-gray-400"><?= esc($b['purpose']) ?></p>
                        <?php endif ?>
                        <?php if ($b['student_count']): ?>
                        <p class="text-xs text-emerald-600 mt-0.5">
                            <i class="fas fa-users mr-1"></i><?= $b['student_count'] ?> siswa · <?= esc($b['activity']) ?>
                        </p>
                        <?php endif ?>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <?php if ($canApprove && $b['status'] === 'Menunggu'): ?>
                    <form method="POST" action="<?= base_url('lab/approve/' . $b['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">
                            <i class="fas fa-check text-xs"></i> Setuju
                        </button>
                    </form>
                    <form method="POST" action="<?= base_url('lab/reject/' . $b['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                            <i class="fas fa-times text-xs"></i> Tolak
                        </button>
                    </form>
                    <?php endif ?>
                    <?php if ($canApprove && $b['status'] === 'Disetujui' && !$b['student_count']): ?>
                    <button onclick="openVisitModal(<?= $b['id'] ?>)"
                            class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                        <i class="fas fa-clipboard-list text-xs"></i> Lapor
                    </button>
                    <?php endif ?>
                    <form method="POST" action="<?= base_url('lab/delete/' . $b['id']) ?>"
                          onsubmit="return confirm('Hapus data peminjaman ini?')">
                        <?= csrf_field() ?>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
    <?php endif ?>
</div>

<!-- Modal Laporan Kunjungan -->
<div id="modalVisit" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-clipboard-list" style="color:var(--color-primary)"></i>
            Input Laporan Kunjungan
        </h3>
        <form id="visitForm" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-3 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Siswa</label>
                    <input type="number" name="student_count" min="0" required placeholder="0"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Aktivitas</label>
                    <input type="text" name="activity" required placeholder="Praktikum, demonstrasi, dll..."
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalVisit').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function openVisitModal(bookingId) {
    document.getElementById('visitForm').action = `<?= base_url('lab/visit/') ?>${bookingId}`;
    document.getElementById('modalVisit').classList.remove('hidden');
}
document.getElementById('modalVisit')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
<?php $this->endSection() ?>