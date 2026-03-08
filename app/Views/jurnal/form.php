<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$item      = $item      ?? [];
$jadwal    = $jadwal    ?? [];
$kelasList = $kelasList ?? [];
$teacherId = $teacherId ?? null;
$isEdit    = $mode === 'edit';
$action    = $isEdit ? base_url('jurnal/update/' . $item['id']) : base_url('jurnal/store');

// Kumpulkan mapel unik dari jadwal untuk datalist
$mapelList = array_unique(array_column($jadwal, 'subject'));
sort($mapelList);
?>

<!-- Flash errors -->
<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <ul class="text-sm space-y-0.5">
        <?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('jurnal') ?>" class="hover:text-gray-600">Jurnal Mengajar</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Input' ?> Jurnal</span>
</div>

<div class="max-w-2xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="teacher_id" value="<?= $teacherId ?? ($item['teacher_id'] ?? '') ?>">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-book-open text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Input' ?> Jurnal Mengajar</h3>
            </div>

            <div class="p-5 space-y-4">

                <!-- Tanggal + Jadwal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Tanggal <span class="text-red-400">*</span>
                        </label>
                        <input type="date" name="date"
                               value="<?= esc(old('date', $item['date'] ?? date('Y-m-d'))) ?>"
                               required
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>

                    <!-- Pilih dari jadwal (shortcut) -->
                    <?php if (!empty($jadwal)): ?>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Dari Jadwal <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <select id="jadwalPicker" onchange="fillFromJadwal(this)"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <option value="">-- Pilih jadwal --</option>
                            <?php foreach ($jadwal as $j): ?>
                            <option value="<?= $j['id'] ?>"
                                    data-subject="<?= esc($j['subject']) ?>"
                                    data-class="<?= $j['class_id'] ?>"
                                    data-grade="<?= $j['grade'] ?>">
                                <?= esc($j['subject']) ?> — <?= esc($j['nama_kelas'] ?? $j['grade']) ?> (<?= $j['day'] ?>)
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Mata Pelajaran <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="subject" id="subjectInput"
                           value="<?= esc(old('subject', $item['subject'] ?? '')) ?>"
                           placeholder="Nama mata pelajaran"
                           required list="mapelList"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    <datalist id="mapelList">
                        <?php foreach ($mapelList as $m): ?>
                        <option value="<?= esc($m) ?>">
                        <?php endforeach ?>
                    </datalist>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kelas</label>
                    <select name="class_id" id="classInput"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            <?= old('class_id', $item['class_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Topik / Materi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Materi / Topik <span class="text-red-400">*</span>
                    </label>
                    <textarea name="topic" rows="4" required
                              placeholder="Tuliskan materi yang diajarkan hari ini..."
                              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('topic', $item['topic'] ?? '')) ?></textarea>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="notes" rows="3"
                              placeholder="Kendala, saran, atau catatan tambahan..."
                              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('notes', $item['notes'] ?? '')) ?></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('jurnal') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-paper-plane' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Jurnal' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
// Map jadwal id → data
const jadwalData = <?= json_encode(array_combine(
    array_column($jadwal, 'id'),
    array_map(fn($j) => ['subject' => $j['subject'], 'class_id' => $j['class_id']], $jadwal)
)) ?>;

function fillFromJadwal(sel) {
    const val = sel.value;
    if (!val || !jadwalData[val]) return;
    const d = jadwalData[val];
    document.getElementById('subjectInput').value = d.subject;
    const classSelect = document.getElementById('classInput');
    if (d.class_id) {
        for (let opt of classSelect.options) {
            opt.selected = opt.value == d.class_id;
        }
    }
}
</script>
<?php $this->endSection() ?>