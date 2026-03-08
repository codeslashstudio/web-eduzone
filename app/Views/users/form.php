<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$mode   = $mode   ?? 'add';
$user   = $user   ?? [];
$roles  = $roles  ?? [];
$isEdit = $mode === 'edit';
$isSelf = $isEdit && ($user['id'] == session()->get('user_id'));
$action = $isEdit ? base_url('users/update/' . $user['id']) : base_url('users/store');
?>

<!-- Flash errors -->
<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex gap-3">
    <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
    <ul class="text-sm space-y-0.5">
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
        <li><?= esc($e) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
    <a href="<?= base_url('users') ?>" class="hover:text-gray-600">Manajemen User</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-700 font-semibold"><?= $isEdit ? 'Edit User' : 'Tambah User' ?></span>
</div>

<div class="max-w-xl">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-user-cog text-sm" style="color:var(--color-primary)"></i>
                <h3 class="font-bold text-gray-900"><?= $isEdit ? 'Edit' : 'Tambah' ?> User</h3>
            </div>

            <div class="p-5 space-y-4">
                <!-- Avatar preview -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold"
                         style="background:var(--color-primary)" id="avatarPreview">
                        <?= $isEdit ? strtoupper(substr($user['username'], 0, 1)) : '?' ?>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800" id="previewName"><?= esc($user['username'] ?? 'Username baru') ?></p>
                        <p class="text-xs text-gray-400" id="previewRole"><?= $roles[$user['role'] ?? ''] ?? 'Pilih role' ?></p>
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Username <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="username"
                           value="<?= esc(old('username', $user['username'] ?? '')) ?>"
                           placeholder="contoh: budi.santoso"
                           required
                           oninput="document.getElementById('previewName').textContent = this.value || 'Username baru'; document.getElementById('avatarPreview').textContent = this.value ? this.value[0].toUpperCase() : '?'"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email"
                           value="<?= esc(old('email', $user['email'] ?? '')) ?>"
                           placeholder="email@sekolah.sch.id"
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Password <?= $isEdit ? '<span class="text-gray-400 font-normal">(kosongkan jika tidak diganti)</span>' : '<span class="text-red-400">*</span>' ?>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                               placeholder="<?= $isEdit ? 'Kosongkan jika tidak diganti' : 'Minimal 6 karakter' ?>"
                               <?= !$isEdit ? 'required' : '' ?>
                               class="w-full px-4 py-2.5 pr-11 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all">
                        <button type="button" onclick="togglePass()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="passIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <?php if ($isSelf): ?>
                    <input type="hidden" name="role" value="<?= $user['role'] ?>">
                    <div class="px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-500">
                        <?= $roles[$user['role']] ?? $user['role'] ?> <span class="text-xs">(tidak dapat diubah)</span>
                    </div>
                    <?php else: ?>
                    <select name="role" required
                            onchange="document.getElementById('previewRole').textContent = this.options[this.selectedIndex].text"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 transition-all bg-white">
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $slug => $lbl): ?>
                        <option value="<?= $slug ?>" <?= old('role', $user['role'] ?? '') === $slug ? 'selected' : '' ?>>
                            <?= $lbl ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                    <?php endif ?>
                </div>

                <!-- Status aktif -->
                <?php if (!$isSelf): ?>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all">
                        <input type="checkbox" name="is_active" value="1"
                               <?= old('is_active', $user['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-4 h-4 rounded" style="accent-color:#10b981">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">User Aktif</p>
                            <p class="text-xs text-gray-400">User dapat login ke sistem</p>
                        </div>
                    </label>
                </div>
                <?php endif ?>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                <a href="<?= base_url('users') ?>"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus' ?> text-xs"></i>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function togglePass() {
    const inp  = document.getElementById('passwordInput');
    const icon = document.getElementById('passIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
<?php $this->endSection() ?>