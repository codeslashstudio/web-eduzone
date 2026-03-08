<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode   = $mode ?? 'add';
$item   = $item ?? [];
$isEdit = $mode === 'edit';
$action = $isEdit ? base_url('inventaris/update/' . $item['id']) : base_url('inventaris/store');
?>

<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('inventaris') ?>" class="hover:text-gray-600">Inventaris</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit' : 'Tambah' ?> Barang</span>
</div>

<div class="max-w-lg">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-box text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> Barang</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Barang <span class="text-red-400">*</span></label>
                    <input type="text" name="item_name" required
                           value="<?= esc(old('item_name', $item['item_name'] ?? '')) ?>"
                           placeholder="contoh: Proyektor Epson, Kursi Laboratorium..."
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jumlah <span class="text-red-400">*</span></label>
                        <input type="number" name="quantity" min="0" required
                               value="<?= esc(old('quantity', $item['quantity'] ?? 1)) ?>"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kondisi <span class="text-red-400">*</span></label>
                        <select name="condition" required
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                            <?php foreach (['Baik','Rusak Ringan','Rusak Berat'] as $c): ?>
                            <option value="<?= $c ?>" <?= old('condition', $item['condition'] ?? 'Baik') === $c ? 'selected' : '' ?>>
                                <?= $c ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lokasi / Ruangan</label>
                    <input type="text" name="location"
                           value="<?= esc(old('location', $item['location'] ?? '')) ?>"
                           placeholder="contoh: Lab IPA, Gudang, Ruang Guru..."
                           list="locationList"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                    <datalist id="locationList">
                        <option value="Lab IPA"><option value="Lab Komputer"><option value="Lab Bahasa">
                        <option value="Ruang Guru"><option value="Ruang Kelas"><option value="Gudang">
                        <option value="Perpustakaan"><option value="Ruang TU"><option value="Aula">
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan</label>
                    <textarea name="notes" rows="3"
                              placeholder="Merek, tahun pengadaan, kondisi detail..."
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc(old('notes', $item['description'] ?? '')) ?></textarea>
                </div>
            </div>
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?= base_url('inventaris') ?>"
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