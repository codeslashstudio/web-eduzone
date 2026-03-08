<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$exam       = $exam       ?? [];
$soalList   = $soalList   ?? [];
$canEdit    = $canEdit    ?? false;
$canAddSoal = $canAddSoal ?? false;
$optionLabels = ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'];
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('ujian') ?>" class="hover:text-gray-600">Ujian</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= esc($exam['name'] ?? '') ?></span>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Info ujian -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5" data-aos="fade-up">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:var(--color-primary)22">
                <i class="fas fa-file-alt text-xl" style="color:var(--color-primary)"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg"><?= esc($exam['name']) ?></h2>
                <p class="text-sm text-gray-500"><?= esc($exam['subject']) ?> · <?= esc($exam['nama_kelas'] ?? $exam['grade'] . ' ' . $exam['major']) ?></p>
                <div class="flex flex-wrap gap-4 mt-2 text-xs text-gray-500">
                    <span><i class="fas fa-calendar mr-1 text-gray-400"></i><?= date('d F Y', strtotime($exam['date'])) ?></span>
                    <span><i class="fas fa-clock mr-1 text-gray-400"></i><?= substr($exam['start_time'],0,5) ?> – <?= substr($exam['end_time'],0,5) ?></span>
                    <span><i class="fas fa-user-tie mr-1 text-gray-400"></i><?= esc($exam['supervisor_name'] ?? '-') ?></span>
                    <span><i class="fas fa-question-circle mr-1 text-gray-400"></i><?= count($soalList) ?> soal</span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <?php if ($canAddSoal): ?>
            <a href="<?= base_url('ujian/' . $exam['id'] . '/soal/add') ?>"
               class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
                <i class="fas fa-plus text-xs"></i> Tambah Soal
            </a>
            <?php endif ?>
            <?php if ($canEdit): ?>
            <a href="<?= base_url('ujian/edit/' . $exam['id']) ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                <i class="fas fa-pencil text-xs"></i> Edit
            </a>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- Daftar soal -->
<div class="space-y-3">
    <?php if (empty($soalList)): ?>
    <div class="bg-white rounded-2xl py-16 text-center text-gray-400 shadow-sm border border-gray-100">
        <i class="fas fa-question-circle text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada soal</p>
        <?php if ($canAddSoal): ?>
        <a href="<?= base_url('ujian/' . $exam['id'] . '/soal/add') ?>"
           class="inline-flex items-center gap-2 mt-3 btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">
            <i class="fas fa-plus text-xs"></i> Tambah Soal Pertama
        </a>
        <?php endif ?>
    </div>
    <?php else: ?>
    <?php foreach ($soalList as $i => $soalItem): /** @var array<string,mixed> $soalItem */ ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="px-5 py-3.5 border-b border-gray-50 flex items-center justify-between">
            <span class="text-xs font-bold text-gray-400">Soal <?= $i + 1 ?></span>
            <div class="flex items-center gap-2">
                <?php if ($soalItem['pembuat']): ?>
                <span class="text-xs text-gray-400"><i class="fas fa-user mr-1"></i><?= esc($soalItem['pembuat']) ?></span>
                <?php endif ?>
                <?php if ($canAddSoal): ?>
                <a href="<?= base_url('ujian/' . $exam['id'] . '/soal/edit/' . $soalItem['id']) ?>"
                   class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                    <i class="fas fa-pencil text-xs"></i>
                </a>
                <form method="POST" action="<?= base_url('ujian/' . $exam['id'] . '/soal/delete/' . $soalItem['id']) ?>"
                      onsubmit="return confirm('Hapus soal ini?')">
                    <?= csrf_field() ?>
                    <button class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
                <?php endif ?>
            </div>
        </div>
        <div class="p-5">
            <p class="text-sm font-semibold text-gray-900 mb-4 leading-relaxed"><?= nl2br(esc($soalItem['question'])) ?></p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <?php foreach (['a','b','c','d'] as $opt): ?>
                <?php $isCorrect = strtolower($soalItem['correct_answer']) === $opt; ?>
                <div class="flex items-start gap-3 p-3 rounded-xl <?= $isCorrect ? 'bg-emerald-50 border border-emerald-200' : 'bg-gray-50 border border-gray-100' ?>">
                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-bold shrink-0
                        <?= $isCorrect ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                        <?= strtoupper($opt) ?>
                    </span>
                    <span class="text-sm <?= $isCorrect ? 'text-emerald-800 font-semibold' : 'text-gray-700' ?>">
                        <?= esc($soalItem['option_' . $opt] ?? '-') ?>
                        <?php if ($isCorrect): ?>
                        <span class="ml-1 text-xs text-emerald-600"><i class="fas fa-check"></i></span>
                        <?php endif ?>
                    </span>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <?php endif ?>
</div>
<?php $this->endSection() ?>