<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$info    = $info    ?? [];
$canEdit = $canEdit ?? false;
$s       = $info;
?>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif ?>

<!-- Header -->
<div class="flex items-start justify-between mb-6 gap-4">
    <div class="flex items-center gap-4">
        <?php if (!empty($s['logo'])): ?>
        <img src="<?= base_url('uploads/sekolah/' . $s['logo']) ?>"
             class="w-16 h-16 object-contain rounded-xl border border-gray-100 shadow-sm" alt="Logo">
        <?php else: ?>
        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center">
            <i class="fas fa-school text-2xl text-gray-300"></i>
        </div>
        <?php endif ?>
        <div>
            <h1 class="text-xl font-bold text-gray-900"><?= esc($s['name'] ?? 'Info Sekolah') ?></h1>
            <div class="flex flex-wrap gap-2 mt-1">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                    <?= esc($s['level'] ?? '') ?> <?= esc($s['status'] ?? '') ?>
                </span>
                <?php if ($s['accreditation'] ?? ''): ?>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                    <?= $s['accreditation'] === 'A' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' ?>">
                    Akreditasi <?= esc($s['accreditation']) ?>
                </span>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php if ($canEdit): ?>
    <a href="<?= base_url('sekolah/edit') ?>"
       class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold shrink-0">
        <i class="fas fa-pencil text-xs"></i> Edit Info
    </a>
    <?php endif ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- Kolom kiri: identitas + kontak -->
    <div class="lg:col-span-2 space-y-5">

        <!-- Identitas Sekolah -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-id-card text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Identitas Sekolah</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ([
                    ['NPSN',       $s['npsn'] ?? '-'],
                    ['NSS',        $s['nss']  ?? '-'],
                    ['Jenjang',    ($s['level'] ?? '-') . ' ' . ($s['status'] ?? '')],
                    ['Akreditasi', $s['accreditation'] ?? '-'],
                    ['Tahun Berdiri', $s['founded_year'] ?? '-'],
                    ['Jam Sekolah', $s['school_hours'] ?? '-'],
                ] as [$lbl, $val]): ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-0.5"><?= $lbl ?></p>
                    <p class="text-sm font-semibold text-gray-800"><?= esc($val) ?></p>
                </div>
                <?php endforeach ?>
                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold text-gray-400 mb-0.5">Alamat Lengkap</p>
                    <p class="text-sm font-semibold text-gray-800">
                        <?= esc($s['address'] ?? '-') ?>,
                        RT <?= esc($s['rt'] ?? '-') ?>/RW <?= esc($s['rw'] ?? '-') ?>,
                        <?= esc($s['village'] ?? '') ?>, Kec. <?= esc($s['district'] ?? '') ?>,
                        <?= esc($s['city'] ?? '') ?>, <?= esc($s['province'] ?? '') ?>
                        <?= $s['postal_code'] ? '(' . esc($s['postal_code']) . ')' : '' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-phone text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Kontak</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ([
                    ['fas fa-phone',   'Telepon', $s['phone']   ?? '-'],
                    ['fas fa-fax',     'Fax',     $s['fax']     ?? '-'],
                    ['fas fa-envelope','Email',   $s['email']   ?? '-'],
                    ['fas fa-globe',   'Website', $s['website'] ?? '-'],
                ] as [$icon, $lbl, $val]): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background:rgba(var(--color-primary-rgb),0.1)">
                        <i class="<?= $icon ?> text-xs" style="color:var(--color-primary)"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400"><?= $lbl ?></p>
                        <p class="text-sm font-semibold text-gray-800"><?= esc($val) ?></p>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- Visi Misi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-star text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Visi, Misi & Motto</h3>
            </div>
            <div class="p-5 space-y-4">
                <?php if ($s['motto'] ?? ''): ?>
                <div class="text-center py-3 px-5 rounded-xl"
                     style="background:rgba(var(--color-primary-rgb),0.08)">
                    <p class="text-sm font-bold italic" style="color:var(--color-primary)">
                        "<?= esc($s['motto']) ?>"
                    </p>
                </div>
                <?php endif ?>
                <?php if ($s['vision'] ?? ''): ?>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Visi</p>
                    <p class="text-sm text-gray-700 leading-relaxed"><?= esc($s['vision']) ?></p>
                </div>
                <?php endif ?>
                <?php if ($s['mission'] ?? ''): ?>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Misi</p>
                    <div class="text-sm text-gray-700 leading-relaxed"><?= nl2br(esc($s['mission'])) ?></div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Kolom kanan: kepala sekolah + bank + jam -->
    <div class="space-y-4">

        <!-- Kepala Sekolah -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-user-tie text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900 text-sm">Kepala Sekolah</h3>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                         style="background:var(--color-primary)">
                        <?= strtoupper(substr($s['principal_name'] ?? 'K', 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm"><?= esc($s['principal_name'] ?? '-') ?></p>
                        <p class="text-xs text-gray-400">Kepala Sekolah</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <p class="text-xs text-gray-400">NIP</p>
                        <p class="text-sm font-semibold text-gray-800 font-mono"><?= esc($s['principal_nip'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">No. HP</p>
                        <p class="text-sm font-semibold text-gray-800"><?= esc($s['principal_phone'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jam Operasional -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-clock text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900 text-sm">Jam Operasional</h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Sesi</span>
                    <span class="text-sm font-bold text-gray-800"><?= esc($s['school_hours'] ?? '-') ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Buka</span>
                    <span class="text-sm font-bold text-gray-800">
                        <?= $s['open_time'] ? substr($s['open_time'], 0, 5) . ' WIB' : '-' ?>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tutup</span>
                    <span class="text-sm font-bold text-gray-800">
                        <?= $s['close_time'] ? substr($s['close_time'], 0, 5) . ' WIB' : '-' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Rekening Bank -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-university text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900 text-sm">Rekening Bank</h3>
            </div>
            <div class="p-5 space-y-2">
                <?php foreach ([
                    ['Bank',     $s['bank_name']           ?? '-'],
                    ['Cabang',   $s['bank_branch']          ?? '-'],
                    ['No. Rek',  $s['bank_account_number']  ?? '-'],
                    ['Atas Nama',$s['bank_account_name']    ?? '-'],
                ] as [$lbl, $val]): ?>
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs text-gray-400 shrink-0"><?= $lbl ?></span>
                    <span class="text-xs font-semibold text-gray-800 text-right"><?= esc($val) ?></span>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>