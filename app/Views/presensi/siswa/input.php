<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📝 Input Presensi Siswa</h4>
        <a href="/presensi-siswa?date=<?= $date ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" action="/presensi-siswa/input" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="<?= $date ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Kelas</label>
                    <select name="grade" class="form-select">
                        <option value="">Semua</option>
                        <option value="X"   <?= $grade === 'X'   ? 'selected' : '' ?>>X</option>
                        <option value="XI"  <?= $grade === 'XI'  ? 'selected' : '' ?>>XI</option>
                        <option value="XII" <?= $grade === 'XII' ? 'selected' : '' ?>>XII</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Jurusan</label>
                    <select name="major_id" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($majors as $major): ?>
                            <option value="<?= $major['id'] ?>" <?= $major_id == $major['id'] ? 'selected' : '' ?>>
                                <?= esc($major['abbreviation']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Rombel</label>
                    <select name="class_group" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" <?= $class_group == '1' ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= $class_group == '2' ? 'selected' : '' ?>>2</option>
                        <option value="3" <?= $class_group == '3' ? 'selected' : '' ?>>3</option>
                        <option value="4" <?= $class_group == '4' ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter Siswa</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <?php if (empty($students)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                    Tidak ada siswa ditemukan. Silakan pilih filter kelas terlebih dahulu.
                </div>
            <?php else: ?>
                <form method="POST" action="/presensi-siswa/store">
                    <?= csrf_field() ?>
                    <input type="hidden" name="date" value="<?= $date ?>">
                    <input type="hidden" name="grade" value="<?= $grade ?>">
                    <input type="hidden" name="major_id" value="<?= $major_id ?>">
                    <input type="hidden" name="class_group" value="<?= $class_group ?>">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th width="150">Status</th>
                                    <th width="110">Check In</th>
                                    <th width="110">Check Out</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $i => $student): ?>
                                    <?php $existing = $existingMap[$student['id']] ?? null; ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td class="fw-semibold"><?= esc($student['full_name']) ?></td>
                                        <td class="text-muted small"><?= esc($student['nis'] ?? '-') ?></td>
                                        <td class="small"><?= $student['grade'] . ' ' . ($student['major'] ?? '') . '-' . $student['class_group'] ?></td>
                                        <td>
                                            <input type="hidden" name="student_id[]" value="<?= $student['id'] ?>">
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
                            Total: <strong><?= count($students) ?> siswa</strong>
                        </div>
                        <div class="d-flex gap-2">
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
document.getElementById('btnAllHadir')?.addEventListener('click', function() {
    document.querySelectorAll('.status-select').forEach(s => s.value = 'Hadir');
});
</script>

<?= $this->endSection() ?>
