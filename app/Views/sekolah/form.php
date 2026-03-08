<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php $s = $info ?? []; ?>

<!-- Flash -->
<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif ?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('sekolah') ?>" class="hover:text-gray-600">Info Sekolah</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold">Edit</span>
</div>

<form method="POST" action="<?= base_url('sekolah/update') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="space-y-5">

        <!-- ======= IDENTITAS ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-id-card text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Identitas Sekolah</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Sekolah <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?= esc($s['name'] ?? '') ?>" required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>

                <?php foreach ([
                    ['NPSN',  'npsn',  '8 digit'],
                    ['NSS',   'nss',   '12 digit'],
                    ['Tahun Berdiri', 'founded_year', 'contoh: 1985'],
                ] as [$lbl, $name, $ph]): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5"><?= $lbl ?></label>
                    <input type="text" name="<?= $name ?>" value="<?= esc($s[$name] ?? '') ?>" placeholder="<?= $ph ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php endforeach ?>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenjang</label>
                    <select name="level" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <?php foreach (['SD','SMP','SMA','SMK'] as $l): ?>
                        <option value="<?= $l ?>" <?= ($s['level'] ?? '') === $l ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <?php foreach (['Negeri','Swasta'] as $st): ?>
                        <option value="<?= $st ?>" <?= ($s['status'] ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Akreditasi</label>
                    <select name="accreditation" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <?php foreach (['A','B','C','Belum Terakreditasi'] as $a): ?>
                        <option value="<?= $a ?>" <?= ($s['accreditation'] ?? '') === $a ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Logo -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Logo Sekolah</label>
                    <div class="flex items-center gap-4">
                        <?php if (!empty($s['logo'])): ?>
                        <img src="<?= base_url('uploads/sekolah/' . $s['logo']) ?>"
                             class="w-14 h-14 object-contain rounded-xl border border-gray-100" alt="Logo">
                        <?php endif ?>
                        <label class="cursor-pointer flex items-center gap-2 px-4 py-2.5 border border-dashed border-gray-300 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all text-sm text-gray-500">
                            <i class="fas fa-upload text-xs"></i>
                            <span>Pilih file logo</span>
                            <input type="file" name="logo" accept="image/*" class="hidden">
                        </label>
                        <p class="text-xs text-gray-400">PNG/JPG, maks. 1MB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======= ALAMAT ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Alamat</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jalan</label>
                    <input type="text" name="address" value="<?= esc($s['address'] ?? '') ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php foreach ([
                    ['RT','rt',''],['RW','rw',''],
                    ['Kelurahan/Desa','village',''],['Kecamatan','district',''],
                    ['Kota/Kabupaten','city',''],['Provinsi','province',''],
                    ['Kode Pos','postal_code','5 digit'],
                ] as [$lbl,$name,$ph]): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5"><?= $lbl ?></label>
                    <input type="text" name="<?= $name ?>" value="<?= esc($s[$name] ?? '') ?>" placeholder="<?= $ph ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- ======= KONTAK ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-phone text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Kontak</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ([
                    ['Telepon','phone','031-xxx'],['Fax','fax',''],
                    ['Email','email','info@sekolah.sch.id'],['Website','website','www.sekolah.sch.id'],
                ] as [$lbl,$name,$ph]): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5"><?= $lbl ?></label>
                    <input type="text" name="<?= $name ?>" value="<?= esc($s[$name] ?? '') ?>" placeholder="<?= $ph ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- ======= KEPALA SEKOLAH ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-user-tie text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Kepala Sekolah</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php foreach ([
                    ['Nama Lengkap','principal_name','Nama + gelar'],
                    ['NIP','principal_nip','18 digit'],
                    ['No. HP','principal_phone','08xx'],
                ] as [$lbl,$name,$ph]): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5"><?= $lbl ?></label>
                    <input type="text" name="<?= $name ?>" value="<?= esc($s[$name] ?? '') ?>" placeholder="<?= $ph ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- ======= JAM OPERASIONAL ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-clock text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Jam Operasional</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sesi Belajar</label>
                    <select name="school_hours" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white">
                        <?php foreach (['Pagi','Siang','Pagi-Siang'] as $sh): ?>
                        <option value="<?= $sh ?>" <?= ($s['school_hours'] ?? '') === $sh ? 'selected' : '' ?>><?= $sh ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Buka</label>
                    <input type="time" name="open_time" value="<?= esc(substr($s['open_time'] ?? '', 0, 5)) ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jam Tutup</label>
                    <input type="time" name="close_time" value="<?= esc(substr($s['close_time'] ?? '', 0, 5)) ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
            </div>
        </div>

        <!-- ======= BANK ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-university text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Rekening Bank</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ([
                    ['Nama Bank','bank_name','Bank Mandiri'],
                    ['Cabang','bank_branch','Surabaya Gubeng'],
                    ['No. Rekening','bank_account_number',''],
                    ['Atas Nama','bank_account_name',''],
                ] as [$lbl,$name,$ph]): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5"><?= $lbl ?></label>
                    <input type="text" name="<?= $name ?>" value="<?= esc($s[$name] ?? '') ?>" placeholder="<?= $ph ?>"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- ======= VISI MISI ======= -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-star text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900">Visi, Misi & Motto</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Motto</label>
                    <input type="text" name="motto" value="<?= esc($s['motto'] ?? '') ?>"
                           placeholder="Motto singkat sekolah"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Visi</label>
                    <textarea name="vision" rows="3"
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc($s['vision'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Misi</label>
                    <textarea name="mission" rows="5" placeholder="Tulis setiap poin misi di baris baru"
                              class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 resize-none"><?= esc($s['mission'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between gap-3" data-aos="fade-up">
            <a href="<?= base_url('sekolah') ?>"
               class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</form>
<?php $this->endSection() ?>