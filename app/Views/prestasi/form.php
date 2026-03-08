<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode      = $mode      ?? 'add';
$item      = $item      ?? [];
$kelasList = $kelasList ?? [];
$isEdit    = $mode === 'edit';
$action    = $isEdit ? base_url('prestasi/update/' . $item['id']) : base_url('prestasi/store');

$levels = ['Sekolah','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional'];
?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('prestasi') ?>" class="hover:text-gray-600">Prestasi Siswa</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Tambah' ?> Prestasi</span>
</div>

<div class="max-w-xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-trophy text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> Prestasi</h3>
            </div>

            <div class="p-5 space-y-4">
                <!-- Pilih siswa -->
                <?php if ($isEdit): ?>
                <input type="hidden" name="student_id" value="<?= $item['student_id'] ?>">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($item['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($item['full_name']) ?></p>
                        <p class="text-xs text-gray-400">NIS: <?= esc($item['nis'] ?? '-') ?></p>
                    </div>
                </div>
                <?php else: ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Siswa <span class="text-red-400">*</span>
                    </label>
                    <input type="hidden" name="student_id" id="studentId">
                    <div class="relative">
                        <input type="text" id="studentSearch"
                               placeholder="Ketik nama atau NIS siswa..."
                               autocomplete="off"
                               class="w-full px-4 py-2.5 pr-10 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                        <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                    <div id="studentResults" class="hidden mt-1 border border-gray-200 rounded-xl shadow-lg bg-white overflow-hidden z-10 relative"></div>
                    <div id="studentSelected" class="hidden mt-2 flex items-center gap-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        <span id="studentSelectedName" class="text-sm font-semibold text-emerald-700"></span>
                        <button type="button" onclick="clearStudent()" class="ml-auto text-xs text-gray-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif ?>

                <!-- Judul prestasi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Judul Prestasi <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title"
                           value="<?= esc(old('title', $item['title'] ?? '')) ?>"
                           placeholder="contoh: Juara 1 Olimpiade Matematika"
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

                <!-- Level + Tahun -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Tingkat <span class="text-red-400">*</span>
                        </label>
                        <select name="level" required
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <?php foreach ($levels as $l): ?>
                            <option value="<?= $l ?>" <?= old('level', $item['level'] ?? '') === $l ? 'selected' : '' ?>>
                                <?= $l ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun</label>
                        <input type="number" name="year"
                               value="<?= esc(old('year', $item['year'] ?? date('Y'))) ?>"
                               min="2000" max="<?= date('Y') + 1 ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan</label>
                    <textarea name="description" rows="3"
                              placeholder="Detail lomba, penyelenggara, dll..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('prestasi') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan' : 'Tambah' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
let searchTimeout;
const inp = document.getElementById('studentSearch');
if (inp) {
    inp.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { document.getElementById('studentResults').classList.add('hidden'); return; }
        searchTimeout = setTimeout(() => {
            fetch(`<?= base_url('prestasi/search-siswa') ?>?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById('studentResults');
                    if (!data.length) { box.classList.add('hidden'); return; }
                    box.innerHTML = data.map(s => `
                        <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer flex items-center gap-2 border-b border-gray-50 last:border-0"
                             onclick="selectStudent(${s.id}, '${s.full_name}', '${s.nis}')">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                                 style="background:var(--color-primary)">${s.full_name[0].toUpperCase()}</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">${s.full_name}</p>
                                <p class="text-xs text-gray-400">${s.nama_kelas ?? ''} · NIS: ${s.nis ?? '-'}</p>
                            </div>
                        </div>`).join('');
                    box.classList.remove('hidden');
                });
        }, 300);
    });
}

function selectStudent(id, name, nis) {
    document.getElementById('studentId').value = id;
    document.getElementById('studentSearch').value = name;
    document.getElementById('studentSelectedName').textContent = name + (nis ? ' (NIS: ' + nis + ')' : '');
    document.getElementById('studentSelected').classList.remove('hidden');
    document.getElementById('studentResults').classList.add('hidden');
}

function clearStudent() {
    document.getElementById('studentId').value = '';
    document.getElementById('studentSearch').value = '';
    document.getElementById('studentSelected').classList.add('hidden');
}
</script>
<?php $this->endSection() ?>