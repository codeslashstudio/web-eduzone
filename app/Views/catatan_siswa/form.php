<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode    = $mode    ?? 'add';
$item    = $item    ?? [];
$student = $student ?? null;
$isEdit  = $mode === 'edit';
$action  = $isEdit ? base_url('catatan-siswa/update/' . $item['id']) : base_url('catatan-siswa/store');
?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('catatan-siswa') ?>" class="hover:text-gray-600">Catatan Siswa</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Tambah' ?> Catatan</span>
</div>

<div class="max-w-xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-sticky-note text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> Catatan Siswa</h3>
            </div>

            <div class="p-5 space-y-4">
                <!-- Pilih/tampil siswa -->
                <?php if ($isEdit): ?>
                <input type="hidden" name="student_id" value="<?= $item['student_id'] ?>">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($item['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($item['full_name']) ?></p>
                        <p class="text-xs text-gray-400"><?= esc($item['nama_kelas'] ?? '-') ?></p>
                    </div>
                </div>
                <?php elseif ($student): ?>
                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($student['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-semibold text-emerald-800 text-sm"><?= esc($student['full_name']) ?></p>
                        <p class="text-xs text-emerald-600"><?= esc($student['nama_kelas'] ?? '-') ?></p>
                    </div>
                </div>
                <?php else: ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Siswa <span class="text-red-400">*</span>
                    </label>
                    <input type="hidden" name="student_id" id="studentId">
                    <div class="relative">
                        <input type="text" id="studentSearch" placeholder="Ketik nama atau NIS siswa..."
                               autocomplete="off"
                               class="w-full px-4 py-2.5 pr-10 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                        <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                    <div id="studentResults" class="hidden mt-1 border border-gray-200 rounded-xl shadow-lg bg-white overflow-hidden relative z-10"></div>
                    <div id="studentSelected" class="hidden mt-2 flex items-center gap-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        <span id="studentSelectedName" class="text-sm font-semibold text-emerald-700"></span>
                        <button type="button" onclick="clearStudent()" class="ml-auto text-xs text-gray-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif ?>

                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="date"
                           value="<?= esc(old('date', $item['date'] ?? date('Y-m-d'))) ?>"
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

                <!-- Jenis kejadian -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Jenis Kejadian / Aktivitas <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="activity"
                           value="<?= esc(old('activity', $item['activity'] ?? '')) ?>"
                           placeholder="contoh: Pelanggaran Tata Tertib, Konseling, Penghargaan..."
                           required list="activityList"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    <datalist id="activityList">
                        <option value="Pelanggaran Tata Tertib">
                        <option value="Terlambat">
                        <option value="Bolos">
                        <option value="Perkelahian">
                        <option value="Konseling">
                        <option value="Prestasi Akademik">
                        <option value="Prestasi Non-Akademik">
                        <option value="Catatan Positif">
                    </datalist>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Detail / Keterangan</label>
                    <textarea name="description" rows="4"
                              placeholder="Uraian lengkap kejadian atau catatan..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('catatan-siswa') ?>"
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
            fetch(`<?= base_url('catatan-siswa/search-siswa') ?>?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById('studentResults');
                    if (!data.length) { box.classList.add('hidden'); return; }
                    box.innerHTML = data.map(s => `
                        <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer flex items-center gap-2 border-b border-gray-50 last:border-0"
                             onclick="selectStudent(${s.id}, '${s.full_name}', '${s.nis}', '${s.nama_kelas ?? ''}')">
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
function selectStudent(id, name, nis, kelas) {
    document.getElementById('studentId').value = id;
    document.getElementById('studentSearch').value = name;
    document.getElementById('studentSelectedName').textContent = name + ' — ' + kelas;
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