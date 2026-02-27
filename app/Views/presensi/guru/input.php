<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📝 Input Presensi Guru</h4>
        <a href="/presensi-guru?date=<?= $date ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <!-- Pilih Tanggal -->
            <form method="GET" action="/presensi-guru/input" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Tanggal Presensi</label>
                    <input type="date" name="date" class="form-control" value="<?= $date ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Ganti Tanggal</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <?php if (empty($teachers)): ?>
                <div class="text-center py-5 text-muted">Tidak ada guru aktif ditemukan.</div>
            <?php else: ?>
                <form method="POST" action="/presensi-guru/store">
                    <?= csrf_field() ?>
                    <input type="hidden" name="date" value="<?= $date ?>">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Guru</th>
                                    <th>NIP</th>
                                    <th width="150">Status</th>
                                    <th width="120">Check In</th>
                                    <th width="120">Check Out</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $i => $teacher): ?>
                                    <?php $existing = $existingMap[$teacher['id']] ?? null; ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td class="fw-semibold"><?= esc($teacher['full_name']) ?></td>
                                        <td class="text-muted small"><?= esc($teacher['nip'] ?? '-') ?></td>
                                        <td>
                                            <input type="hidden" name="teacher_id[]" value="<?= $teacher['id'] ?>">
                                            <select name="status[]" class="form-select form-select-sm status-select">
                                                <option value="Hadir" <?= ($existing['status'] ?? 'Hadir') === 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                                <option value="Izin"  <?= ($existing['status'] ?? '') === 'Izin'  ? 'selected' : '' ?>>Izin</option>
                                                <option value="Sakit" <?= ($existing['status'] ?? '') === 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                                <option value="Alpa"  <?= ($existing['status'] ?? '') === 'Alpa'  ? 'selected' : '' ?>>Alpa</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="time" name="check_in[]" class="form-control form-control-sm"
                                                   value="<?= $existing['check_in'] ?? '' ?>">
                                        </td>
                                        <td>
                                            <input type="time" name="check_out[]" class="form-control form-control-sm"
                                                   value="<?= $existing['check_out'] ?? '' ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="notes[]" class="form-control form-control-sm"
                                                   placeholder="Opsional"
                                                   value="<?= esc($existing['notes'] ?? '') ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Total: <strong><?= count($teachers) ?> guru</strong>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Tandai semua hadir -->
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnAllHadir">
                                <i class="bi bi-check-all"></i> Semua Hadir
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Presensi
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('btnAllHadir').addEventListener('click', function() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.value = 'Hadir';
    });
});
</script>

<?= $this->endSection() ?>
