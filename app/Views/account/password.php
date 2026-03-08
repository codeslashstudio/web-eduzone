<?php $this->extend('layout/main') ?>

<?php $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Ubah Password</h1>
        <p class="text-sm text-gray-500 mb-6">Silakan masukkan password saat ini dan password baru.</p>

        <form method="post" action="<?= base_url('password/update') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" class="w-full border border-gray-200 rounded-xl px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="new_password" class="w-full border border-gray-200 rounded-xl px-3 py-2" minlength="6" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="w-full border border-gray-200 rounded-xl px-3 py-2" minlength="6" required>
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="btn-primary px-4 py-2 rounded-xl">Simpan</button>
                <a href="<?= base_url('dashboard') ?>" class="btn-ghost px-4 py-2 rounded-xl">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection() ?>
