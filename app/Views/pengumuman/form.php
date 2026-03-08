<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode    = $mode    ?? 'add';
$item    = $item    ?? [];
$isEdit  = $mode === 'edit';
$action  = $isEdit ? base_url('pengumuman/update/' . $item['id']) : base_url('pengumuman/store');

$allRoles = [
    'kepsek'     => 'Kepala Sekolah',
    'tu'         => 'Tata Usaha',
    'kurikulum'  => 'Kurikulum',
    'guru_mapel' => 'Guru Mapel',
    'wali_kelas' => 'Wali Kelas',
    'kesiswaan'  => 'Kesiswaan',
    'bk'         => 'BK',
    'toolman'    => 'Toolman',
    'siswa'      => 'Siswa',
];
$selectedRoles = $item['visibility'] ? explode(',', $item['visibility']) : [];
?>

<!-- Flash errors -->
<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('pengumuman') ?>" class="hover:text-gray-600">Pengumuman</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-600 font-semibold"><?= $isEdit ? 'Edit' : 'Buat' ?> Pengumuman</span>
</div>

<form method="POST" action="<?= $action ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Form utama -->
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bullhorn text-sm" style="color:var(--color-primary)"></i>
                    Isi Pengumuman
                </h3>

                <!-- Judul -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Judul <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title"
                           value="<?= esc(old('title', $item['title'] ?? '')) ?>"
                           placeholder="Judul pengumuman..."
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                </div>

                <!-- Konten -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Isi Pengumuman <span class="text-red-400">*</span>
                    </label>
                    <textarea name="content" rows="10"
                              placeholder="Tulis isi pengumuman di sini..."
                              required
                              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all resize-none"><?= esc(old('content', $item['content'] ?? '')) ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">Gunakan baris baru untuk paragraf baru.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar pengaturan -->
        <div class="space-y-4">

            <!-- Publish & penting -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 text-sm">
                    <i class="fas fa-cog" style="color:var(--color-primary)"></i>
                    Pengaturan
                </h3>

                <!-- Tanggal tayang -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Tayang</label>
                    <input type="date" name="published_at"
                           value="<?= esc(old('published_at', $item['published_at'] ?? date('Y-m-d'))) ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                </div>

                <!-- Penting -->
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-all">
                    <input type="checkbox" name="is_important" value="1"
                           <?= ($item['is_important'] ?? 0) ? 'checked' : '' ?>
                           class="w-4 h-4 rounded" style="accent-color:#ef4444">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Tandai sebagai Penting</p>
                        <p class="text-xs text-gray-400">Akan ditampilkan di bagian atas</p>
                    </div>
                </label>
            </div>

            <!-- Target penerima -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                <h3 class="font-bold text-gray-900 mb-1 flex items-center gap-2 text-sm">
                    <i class="fas fa-users" style="color:var(--color-primary)"></i>
                    Ditujukan Kepada
                </h3>
                <p class="text-xs text-gray-400 mb-4">Kosongkan = semua pengguna</p>

                <div class="space-y-2">
                    <?php foreach ($allRoles as $slug => $label): ?>
                    <label class="flex items-center gap-3 cursor-pointer p-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        <input type="checkbox" name="visibility[]" value="<?= $slug ?>"
                               <?= in_array($slug, $selectedRoles) ? 'checked' : '' ?>
                               class="w-4 h-4 rounded" style="accent-color:var(--color-primary)">
                        <span class="text-sm text-gray-700 font-medium"><?= $label ?></span>
                    </label>
                    <?php endforeach ?>
                </div>

                <!-- Select all -->
                <button type="button" onclick="toggleAll()"
                    class="mt-3 w-full text-xs font-semibold py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                    Pilih / Batalkan Semua
                </button>
            </div>

            <!-- Action buttons -->
            <div class="flex gap-3" data-aos="fade-up">
                <a href="<?= base_url('pengumuman') ?>"
                   class="flex-1 text-center py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-paper-plane' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan' : 'Terbitkan' ?>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function toggleAll() {
    const checks = document.querySelectorAll('input[name="visibility[]"]');
    const anyUnchecked = Array.from(checks).some(c => !c.checked);
    checks.forEach(c => c.checked = anyUnchecked);
}
</script>
<?php $this->endSection() ?>