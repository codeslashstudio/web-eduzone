<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$list    = $list    ?? [];
$stats   = $stats   ?? [];
$canEdit = $canEdit ?? false;

// Data untuk modal edit — ambil dari request jika ada error
$editData = session()->getFlashdata('edit_data') ?? [];
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
<div class="grid grid-cols-3 gap-4 mb-5">
    <?php foreach ([
        ['Total Jurusan', $stats['total']   ?? 0, 'fa-layer-group',  '#6366f1'],
        ['Aktif',         $stats['aktif']   ?? 0, 'fa-check-circle', '#10b981'],
        ['Nonaktif',      $stats['nonaktif']?? 0, 'fa-times-circle', '#ef4444'],
    ] as [$lbl,$val,$icon,$color]): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:<?= $color ?>22">
            <i class="fas <?= $icon ?> text-base" style="color:<?= $color ?>"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $val ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?= $lbl ?></p>
    </div>
    <?php endforeach ?>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-layer-group" style="color:var(--color-primary)"></i>
                Daftar Jurusan
                <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($list) ?></span>
            </h3>
            <a href="<?= base_url('master/kelas') ?>"
               class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                <i class="fas fa-door-open mr-1"></i> Kelas
            </a>
        </div>
        <?php if ($canEdit): ?>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
            <i class="fas fa-plus text-xs"></i> Tambah Jurusan
        </button>
        <?php endif ?>
    </div>

    <?php if (empty($list)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-layer-group text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Belum ada jurusan</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Jurusan</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Singkatan</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Deskripsi</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kelas</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Siswa</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
                    <?php if ($canEdit): ?>
                    <th class="py-3 px-4 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($list as $j): ?>
                <tr class="hover:bg-gray-50 transition-colors <?= !$j['is_active'] ? 'opacity-60' : '' ?>">
                    <td class="py-3.5 px-4 font-bold text-gray-900"><?= esc($j['name']) ?></td>
                    <td class="py-3.5 px-4">
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700">
                            <?= esc($j['abbreviation']) ?>
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-sm text-gray-500 max-w-xs truncate">
                        <?= esc($j['description'] ?? '-') ?>
                    </td>
                    <td class="py-3.5 px-4 text-center font-semibold text-gray-700"><?= $j['jumlah_kelas'] ?></td>
                    <td class="py-3.5 px-4 text-center font-semibold text-gray-700"><?= $j['jumlah_siswa'] ?></td>
                    <td class="py-3.5 px-4 text-center">
                        <?php if ($canEdit): ?>
                        <form method="POST" action="<?= base_url('master/jurusan/toggle/' . $j['id']) ?>">
                            <?= csrf_field() ?>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg transition-colors
                                    <?= $j['is_active'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>">
                                <?= $j['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?= $j['is_active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $j['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                        <?php endif ?>
                    </td>
                    <?php if ($canEdit): ?>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button onclick="openEdit(<?= htmlspecialchars(json_encode($j)) ?>)"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </button>
                            <form method="POST" action="<?= base_url('master/jurusan/delete/' . $j['id']) ?>"
                                  onsubmit="return confirm('Hapus jurusan <?= esc($j['name']) ?>?')">
                                <?= csrf_field() ?>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<!-- Modal Tambah -->
<?php if ($canEdit): ?>
<div id="modalTambah" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-plus" style="color:var(--color-primary)"></i> Tambah Jurusan
        </h3>
        <form method="POST" action="<?= base_url('master/jurusan/store') ?>">
            <?= csrf_field() ?>
            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Jurusan <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required placeholder="contoh: Ilmu Pengetahuan Alam"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Singkatan <span class="text-red-400">*</span></label>
                    <input type="text" name="abbreviation" required placeholder="IPA, IPS, TKJ..." maxlength="10"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Opsional..."
                              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-pencil text-yellow-500"></i> Edit Jurusan
        </h3>
        <form id="editForm" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Jurusan <span class="text-red-400">*</span></label>
                    <input type="text" id="editName" name="name" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Singkatan <span class="text-red-400">*</span></label>
                    <input type="text" id="editAbbr" name="abbreviation" required maxlength="10"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                    <textarea id="editDesc" name="description" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php endif ?>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function openEdit(data) {
    document.getElementById('editName').value = data.name;
    document.getElementById('editAbbr').value = data.abbreviation;
    document.getElementById('editDesc').value = data.description ?? '';
    document.getElementById('editForm').action = `<?= base_url('master/jurusan/update/') ?>${data.id}`;
    document.getElementById('modalEdit').classList.remove('hidden');
}
['modalTambah','modalEdit'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
<?php $this->endSection() ?>