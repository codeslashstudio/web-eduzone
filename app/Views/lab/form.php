<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$item      = $item      ?? [];
$teacherId = $teacherId ?? null;
$isEdit    = $mode === 'edit';
$action    = $isEdit ? base_url('lab/update/' . $item['id']) : base_url('lab/store');

$labOptions = ['Lab IPA','Lab Komputer','Lab Bahasa','Lab Kimia','Lab Fisika','Lab Biologi','Aula'];
?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('lab') ?>" class="hover:text-gray-600">Peminjaman Lab</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Ajukan Peminjaman</span>
</div>

<div class="max-w-lg">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <?php if ($teacherId): ?>
        <input type="hidden" name="teacher_id" value="<?= $teacherId ?>">
        <?php endif ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-flask text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Formulir Peminjaman Laboratorium</h3>
            </div>
            <div class="p-5 space-y-4">
                <!-- Nama Lab -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">
                        Laboratorium <span class="text-red-400">*</span>
                    </label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <?php foreach ($labOptions as $l): ?>
                        <button type="button" onclick="setLab('<?= $l ?>')"
                                class="lab-btn px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors">
                            <?= $l ?>
                        </button>
                        <?php endforeach ?>
                    </div>
                    <input type="text" name="lab_name" id="labInput" required
                           value="<?= esc(old('lab_name', $item['lab_name'] ?? '')) ?>"
                           placeholder="Atau ketik nama lab lain..."
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="date" required
                           value="<?= esc(old('date', $item['date'] ?? date('Y-m-d'))) ?>"
                           min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

                <!-- Jam -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Mulai <span class="text-red-400">*</span></label>
                        <input type="time" name="start_time" required
                               value="<?= esc(old('start_time', $item['start_time'] ?? '07:00')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Selesai <span class="text-red-400">*</span></label>
                        <input type="time" name="end_time" required
                               value="<?= esc(old('end_time', $item['end_time'] ?? '09:00')) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                </div>

                <!-- Keperluan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Keperluan / Tujuan <span class="text-red-400">*</span>
                    </label>
                    <textarea name="purpose" rows="3" required
                              placeholder="contoh: Praktikum Kimia Kelas XII IPA, Uji coba alat..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('purpose', $item['purpose'] ?? '')) ?></textarea>
                </div>

                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl flex gap-2">
                    <i class="fas fa-info-circle text-yellow-500 shrink-0 mt-0.5"></i>
                    <p class="text-xs text-yellow-700">Peminjaman akan diproses oleh petugas. Pastikan tidak ada konflik jadwal sebelum mengajukan.</p>
                </div>
            </div>
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('lab') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas fa-paper-plane text-xs"></i> Ajukan
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function setLab(val) {
    document.getElementById('labInput').value = val;
    document.querySelectorAll('.lab-btn').forEach(btn => {
        const active = btn.textContent.trim() === val;
        btn.classList.toggle('border-blue-400', active);
        btn.classList.toggle('text-blue-600', active);
        btn.classList.toggle('bg-blue-50', active);
    });
}
const curLab = document.getElementById('labInput')?.value.trim();
if (curLab) {
    document.querySelectorAll('.lab-btn').forEach(btn => {
        if (btn.textContent.trim() === curLab)
            btn.classList.add('border-blue-400','text-blue-600','bg-blue-50');
    });
}
</script>
<?php $this->endSection() ?>