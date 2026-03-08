<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$exam      = $exam      ?? [];
$soal      = $soal      ?? [];
$teacherId = $teacherId ?? null;
$isEdit    = $mode === 'edit';
$action    = $isEdit
    ? base_url('ujian/' . $exam['id'] . '/soal/update/' . $soal['id'])
    : base_url('ujian/' . $exam['id'] . '/soal/store');
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3" data-aos="fade-down">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('ujian') ?>" class="hover:text-gray-600">Ujian</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <a href="<?= base_url('ujian/' . $exam['id']) ?>" class="hover:text-gray-600"><?= esc($exam['name'] ?? '') ?></a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Tambah' ?> Soal</span>
</div>

<!-- Info ujian -->
<div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 mb-5 flex items-center gap-3">
    <i class="fas fa-file-alt text-indigo-500 shrink-0"></i>
    <div class="text-sm">
        <span class="font-bold text-indigo-800"><?= esc($exam['name'] ?? '') ?></span>
        <span class="text-indigo-600 ml-2"><?= esc($exam['subject'] ?? '') ?> · <?= date('d F Y', strtotime($exam['date'])) ?></span>
    </div>
</div>

<div class="max-w-2xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-question-circle text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> Soal</h3>
            </div>
            <div class="p-5 space-y-5">
                <!-- Pertanyaan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Pertanyaan <span class="text-red-400">*</span>
                    </label>
                    <textarea name="question" rows="4" required
                              placeholder="Tuliskan pertanyaan di sini..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('question', $soal['question'] ?? '')) ?></textarea>
                </div>

                <!-- Pilihan jawaban -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-3">
                        Pilihan Jawaban <span class="text-red-400">*</span>
                        <span class="font-normal text-gray-400 ml-1">— centang jawaban yang benar</span>
                    </label>
                    <div class="space-y-2.5">
                        <?php foreach (['a','b','c','d'] as $opt): ?>
                        <?php $isCorrect = strtolower(old('correct_answer', $soal['correct_answer'] ?? '')) === $opt; ?>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0">
                                <input type="radio" name="correct_answer" value="<?= $opt ?>" required
                                       <?= $isCorrect ? 'checked' : '' ?>
                                       class="w-4 h-4 text-emerald-500 cursor-pointer"
                                       onchange="highlightOption()">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-700 option-label-<?= $opt ?>"><?= strtoupper($opt) ?></span>
                            </label>
                            <input type="text" name="option_<?= $opt ?>" required
                                   value="<?= esc(old('option_' . $opt, $soal['option_' . $opt] ?? '')) ?>"
                                   placeholder="Pilihan <?= strtoupper($opt) ?>..."
                                   class="flex-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 option-input-<?= $opt ?>">
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                <a href="<?= base_url('ujian/' . $exam['id']) ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Kembali
                </a>
                <div class="flex gap-2">
                    <?php if (!$isEdit): ?>
                    <button type="submit" name="action" value="save_and_add"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-700 flex items-center gap-2 transition-colors">
                        <i class="fas fa-plus text-xs"></i> Simpan & Tambah Lagi
                    </button>
                    <?php endif ?>
                    <button type="submit" name="action" value="save"
                            class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas <?= $isEdit ? 'fa-save' : 'fa-check' ?> text-xs"></i>
                        <?= $isEdit ? 'Simpan' : 'Selesai' ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function highlightOption() {
    ['a','b','c','d'].forEach(opt => {
        const radio = document.querySelector(`input[name="correct_answer"][value="${opt}"]`);
        const label = document.querySelector(`.option-label-${opt}`);
        const input = document.querySelector(`.option-input-${opt}`);
        if (radio?.checked) {
            label.className = label.className.replace('bg-gray-200 text-gray-700', '');
            label.classList.add('bg-emerald-500', 'text-white');
            input.classList.add('border-emerald-300', 'bg-emerald-50');
        } else {
            label.classList.remove('bg-emerald-500', 'text-white');
            label.classList.add('bg-gray-200', 'text-gray-700');
            input.classList.remove('border-emerald-300', 'bg-emerald-50');
        }
    });
}
// Init on load
highlightOption();
</script>
<?php $this->endSection() ?>