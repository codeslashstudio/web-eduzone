<?php $this->extend('layout/main') ?>
<?php $this->section('content') ?>
<?php
$users      = $users      ?? [];
$stats      = $stats      ?? [];
$roles      = $roles      ?? [];
$search     = $search     ?? '';
$roleFilter = $roleFilter ?? '';
$status     = $status     ?? '';

$roleColors = [
    'superadmin' => 'bg-red-100 text-red-700',
    'kepsek'     => 'bg-purple-100 text-purple-700',
    'tu'         => 'bg-blue-100 text-blue-700',
    'kurikulum'  => 'bg-yellow-100 text-yellow-700',
    'guru_mapel' => 'bg-indigo-100 text-indigo-700',
    'wali_kelas' => 'bg-cyan-100 text-cyan-700',
    'kesiswaan'  => 'bg-orange-100 text-orange-700',
    'bk'         => 'bg-rose-100 text-rose-700',
    'toolman'    => 'bg-gray-100 text-gray-600',
    'siswa'      => 'bg-emerald-100 text-emerald-700',
];
?>

<!-- Flash -->
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
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php foreach ([
        ['Total User',    $stats['total'],    'fa-users',        'var(--color-primary)'],
        ['Aktif',         $stats['aktif'],    'fa-user-check',   '#10b981'],
        ['Nonaktif',      $stats['nonaktif'], 'fa-user-slash',   '#ef4444'],
        ['Login Hari Ini',$stats['hari_ini'], 'fa-sign-in-alt',  '#f59e0b'],
    ] as [$lbl, $val, $icon, $color]): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3"
             style="background:<?= $color ?>22">
            <i class="fas <?= $icon ?> text-base" style="color:<?= $color ?>"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= $val ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?= $lbl ?></p>
    </div>
    <?php endforeach ?>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
    <!-- Toolbar -->
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-users-cog text-accent"></i>
            Daftar User
            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"><?= count($users) ?></span>
        </h3>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <!-- Search & Filter Form -->
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <div class="relative">
                    <input type="text" name="search" value="<?= esc($search) ?>"
                           placeholder="Cari username/email..."
                           class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none w-48">
                    <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
                <select name="role" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                    <option value="">Semua Role</option>
                    <?php foreach ($roles as $slug => $lbl): ?>
                    <option value="<?= $slug ?>" <?= $roleFilter === $slug ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach ?>
                </select>
                <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <button type="submit" class="btn-primary px-3 py-2 rounded-xl text-sm font-semibold">
                    <i class="fas fa-search text-xs"></i>
                </button>
                <?php if ($search || $roleFilter || $status !== ''): ?>
                <a href="<?= base_url('users') ?>" class="px-3 py-2 rounded-xl text-sm text-gray-500 border border-gray-200 hover:bg-gray-50">
                    <i class="fas fa-times text-xs"></i>
                </a>
                <?php endif ?>
            </form>
            <a href="<?= base_url('users/add') ?>"
               class="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap">
                <i class="fas fa-plus text-xs"></i> Tambah User
            </a>
        </div>
    </div>

    <?php if (empty($users)): ?>
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-users text-5xl text-gray-200 mb-4"></i>
        <p class="font-semibold">Tidak ada user ditemukan</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">User</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Role</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Login Terakhir</th>
                    <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($users as $u):
                    $isSelf = ($u['id'] == session()->get('user_id'));
                ?>
                <tr class="hover:bg-gray-50 transition-colors <?= !$u['is_active'] ? 'opacity-60' : '' ?>">
                    <!-- User -->
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                                 style="background:var(--color-primary)">
                                <?= strtoupper(substr($u['username'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    <?= esc($u['username']) ?>
                                    <?php if ($isSelf): ?>
                                    <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full ml-1">Anda</span>
                                    <?php endif ?>
                                </p>
                                <p class="text-xs text-gray-400"><?= esc($u['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <!-- Role -->
                    <td class="py-3.5 px-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg <?= $roleColors[$u['role']] ?? 'bg-gray-100 text-gray-600' ?>">
                            <?= $roles[$u['role']] ?? $u['role'] ?>
                        </span>
                    </td>
                    <!-- Status -->
                    <td class="py-3.5 px-4">
                        <?php if ($u['is_active']): ?>
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                        </span>
                        <?php else: ?>
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-red-500">
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Nonaktif
                        </span>
                        <?php endif ?>
                    </td>
                    <!-- Login terakhir -->
                    <td class="py-3.5 px-4 text-xs text-gray-400">
                        <?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '<span class="text-gray-300">Belum pernah</span>' ?>
                    </td>
                    <!-- Aksi -->
                    <td class="py-3.5 px-4">
                        <div class="flex items-center justify-end gap-1">
                            <!-- Edit -->
                            <a href="<?= base_url('users/edit/' . $u['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-yellow-100 hover:text-yellow-600 text-gray-500 transition-colors">
                                <i class="fas fa-pencil text-xs"></i>
                            </a>

                            <!-- Toggle status -->
                            <?php if (!$isSelf): ?>
                            <form method="POST" action="<?= base_url('users/toggle/' . $u['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 transition-colors
                                    <?= $u['is_active'] ? 'hover:bg-orange-100 hover:text-orange-500 text-gray-500' : 'hover:bg-emerald-100 hover:text-emerald-600 text-gray-500' ?>"
                                    title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <i class="fas <?= $u['is_active'] ? 'fa-ban' : 'fa-check' ?> text-xs"></i>
                                </button>
                            </form>
                            <?php endif ?>

                            <!-- Reset password -->
                            <button onclick="openResetModal(<?= $u['id'] ?>, '<?= esc($u['username']) ?>')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 text-gray-500 transition-colors"
                                title="Reset Password">
                                <i class="fas fa-key text-xs"></i>
                            </button>

                            <!-- Delete -->
                            <?php if (!$isSelf): ?>
                            <form method="POST" action="<?= base_url('users/delete/' . $u['id']) ?>"
                                  onsubmit="return confirm('Hapus user <?= esc($u['username']) ?>? Tindakan ini tidak dapat dibatalkan.')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-500 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fas fa-key text-sm" style="color:var(--color-primary)"></i>
            Reset Password
        </h3>
        <p class="text-sm text-gray-500 mb-4">User: <strong id="resetUsername"></strong></p>
        <form id="resetForm" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru</label>
                <input type="password" name="new_password" id="newPasswordInput"
                       placeholder="Minimal 6 karakter"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeResetModal()"
                    class="flex-1 py-2.5 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 btn-primary py-2.5 rounded-xl text-sm font-semibold">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection() ?>
<?php $this->section('scripts') ?>
<script>
function openResetModal(id, username) {
    document.getElementById('resetUsername').textContent = username;
    document.getElementById('resetForm').action = `<?= base_url('users/reset-password/') ?>${id}`;
    document.getElementById('newPasswordInput').value = '';
    document.getElementById('resetModal').classList.remove('hidden');
}
function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}
document.getElementById('resetModal').addEventListener('click', function(e) {
    if (e.target === this) closeResetModal();
});
</script>
<?php $this->endSection() ?>