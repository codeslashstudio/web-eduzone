<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$pengajuan = $pengajuan ?? [];
$canEdit   = $canEdit   ?? false;
$status    = $status    ?? 'Pending';

$statusColors = [
    'Pending'  => 'bg-yellow-100 text-yellow-700',
    'Approved' => 'bg-emerald-100 text-emerald-700',
    'Rejected' => 'bg-red-100 text-red-700',
];
$statusIcons = [
    'Pending'  => 'fa-clock',
    'Approved' => 'fa-check-circle',
    'Rejected' => 'fa-times-circle',
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

<!-- Header + Filter -->
<div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Pengajuan Anggaran</h1>
        <p class="text-sm text-gray-400 mt-0.5"><?= count($pengajuan) ?> pengajuan ditemukan</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <form method="GET" class="flex gap-2">
            <?php foreach (['Pending','Approved','Rejected'] as $s): ?>
            <a href="?status=<?= $s ?>"
               class="px-3 py-2 rounded-xl text-sm font-semibold transition-all
               <?= $status === $s ? 'btn-primary' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                <?= $s ?>
            </a>
            <?php endforeach ?>
        </form>
        <?php if ($canEdit): ?>
        <button onclick="document.getElementById('modalTambahPengajuan').classList.remove('hidden')"
            class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
            <i class="fas fa-plus text-xs"></i> Ajukan Anggaran
        </button>
        <?php endif ?>
    </div>
</div>

<!-- List -->
<div class="space-y-3">
    <?php if (empty($pengajuan)): ?>
    <div class="bg-white rounded-2xl py-16 text-center text-gray-400 shadow-sm border border-gray-100">
        <i class="fas fa-file-invoice text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Tidak ada pengajuan dengan status "<?= $status ?>"</p>
    </div>
    <?php else: ?>
    <?php foreach ($pengajuan as $p): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?= $statusColors[$p['status']] ?? 'bg-gray-100 text-gray-600' ?>">
                            <i class="fas <?= $statusIcons[$p['status']] ?? 'fa-circle' ?> mr-1"></i>
                            <?= $p['status'] ?>
                        </span>
                        <?php if ($p['nama_kategori'] ?? ''): ?>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            <?= esc($p['nama_kategori']) ?>
                        </span>
                        <?php endif ?>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1"><?= esc($p['judul']) ?></h3>
                    <?php if ($p['keperluan']): ?>
                    <p class="text-sm text-gray-500 mb-2"><?= esc($p['keperluan']) ?></p>
                    <?php endif ?>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                        <span><i class="fas fa-calendar mr-1"></i><?= date('d F Y', strtotime($p['tanggal_pengajuan'])) ?></span>
                        <?php if ($p['reviewed_at']): ?>
                        <span><i class="fas fa-check mr-1"></i>Ditinjau: <?= date('d F Y', strtotime($p['reviewed_at'])) ?></span>
                        <?php endif ?>
                    </div>
                    <?php if ($p['catatan_reviewer'] && $p['status'] !== 'Pending'): ?>
                    <div class="mt-2 p-2.5 rounded-lg <?= $p['status'] === 'Approved' ? 'bg-emerald-50' : 'bg-red-50' ?>">
                        <p class="text-xs text-gray-600"><strong>Catatan:</strong> <?= esc($p['catatan_reviewer']) ?></p>
                    </div>
                    <?php endif ?>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-lg font-bold text-gray-900">Rp <?= number_format($p['jumlah_diajukan'], 0, ',', '.') ?></p>
                    <?php if ($canEdit && $p['status'] === 'Pending'): ?>
                    <div class="flex gap-1 mt-2 justify-end">
                        <button onclick="openApproveModal(<?= $p['id_pengajuan'] ?>, '<?= esc($p['judul']) ?>')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">
                            <i class="fas fa-check text-xs"></i> Setuju
                        </button>
                        <button onclick="openRejectModal(<?= $p['id_pengajuan'] ?>, '<?= esc($p['judul']) ?>')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                            <i class="fas fa-times text-xs"></i> Tolak
                        </button>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <?php endif ?>
</div>

<!-- Modal Approve -->
<div id="modalApprove" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i> Setujui Pengajuan
        </h3>
        <p class="text-sm text-gray-500 mb-4" id="approveTitle"></p>
        <form id="approveForm" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan (opsional)</label>
                <textarea name="catatan" rows="3" placeholder="Catatan persetujuan..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalApprove').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-emerald-500 text-white hover:bg-emerald-600">
                    Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="modalReject" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-times-circle text-red-500"></i> Tolak Pengajuan
        </h3>
        <p class="text-sm text-gray-500 mb-4" id="rejectTitle"></p>
        <form id="rejectForm" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alasan Penolakan <span class="text-red-400">*</span></label>
                <textarea name="catatan" rows="3" required placeholder="Alasan penolakan..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalReject').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-red-500 text-white hover:bg-red-600">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Pengajuan -->
<?php if ($canEdit): ?>
<div id="modalTambahPengajuan" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-file-invoice" style="color:var(--color-primary)"></i>
            Ajukan Anggaran
        </h3>
        <form method="POST" action="<?= base_url('keuangan/pengajuan/store') ?>">
            <?= csrf_field() ?>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Judul Pengajuan <span class="text-red-400">*</span></label>
                    <input type="text" name="judul" required placeholder="Judul singkat pengajuan"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="jumlah_diajukan" required min="0" placeholder="0"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keperluan</label>
                    <textarea name="keperluan" rows="3" placeholder="Uraian keperluan anggaran..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="document.getElementById('modalTambahPengajuan').classList.add('hidden')"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">Ajukan</button>
            </div>
        </form>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function openApproveModal(id, title) {
    document.getElementById('approveTitle').textContent = title;
    document.getElementById('approveForm').action = `<?= base_url('keuangan/persetujuan/approve/') ?>${id}`;
    document.getElementById('modalApprove').classList.remove('hidden');
}
function openRejectModal(id, title) {
    document.getElementById('rejectTitle').textContent = title;
    document.getElementById('rejectForm').action = `<?= base_url('keuangan/persetujuan/reject/') ?>${id}`;
    document.getElementById('modalReject').classList.remove('hidden');
}
['modalApprove','modalReject','modalTambahPengajuan'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
<?php $this->endSection() ?>