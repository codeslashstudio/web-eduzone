<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">✏️ Edit Presensi Siswa</h4>
        <a href="/presensi-siswa?date=<?= $attendance['date'] ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-white">
            <h6 class="mb-0">
                <i class="bi bi-person-circle me-2"></i>
                <?= esc($student['full_name']) ?>
                <span class="badge bg-secondary ms-2"><?= $student['grade'] . ' ' . ($student['major'] ?? '') . '-' . $student['class_group'] ?></span>
                <span class="text-muted fw-normal small ms-2"><?= date('d F Y', strtotime($attendance['date'])) ?></span>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="/presensi-siswa/update/<?= $attendance['id'] ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Kehadiran</label>
                    <select name="status" class="form-select" required>
                        <option value="Hadir" <?= $attendance['status'] === 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                        <option value="Izin"  <?= $attendance['status'] === 'Izin'  ? 'selected' : '' ?>>Izin</option>
                        <option value="Sakit" <?= $attendance['status'] === 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                        <option value="Alpa"  <?= $attendance['status'] === 'Alpa'  ? 'selected' : '' ?>>Alpa</option>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Check In</label>
                        <input type="time" name="check_in" class="form-control"
                               value="<?= $attendance['check_in'] ?? '' ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Check Out</label>
                        <input type="time" name="check_out" class="form-control"
                               value="<?= $attendance['check_out'] ?? '' ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Opsional"><?= esc($attendance['notes'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="/presensi-siswa?date=<?= $attendance['date'] ?>" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
