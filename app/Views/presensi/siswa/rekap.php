<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📊 Rekap Presensi Siswa</h4>
        <a href="/presensi-siswa" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="/presensi-siswa/rekap" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="month" class="form-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="year" class="form-select">
                        <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select name="grade" class="form-select">
                        <option value="">Semua</option>
                        <option value="X"   <?= $grade === 'X'   ? 'selected' : '' ?>>X</option>
                        <option value="XI"  <?= $grade === 'XI'  ? 'selected' : '' ?>>XI</option>
                        <option value="XII" <?= $grade === 'XII' ? 'selected' : '' ?>>XII</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Jurusan</label>
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
                    <label class="form-label fw-semibold">Rombel</label>
                    <select name="class_group" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" <?= $class_group == '1' ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= $class_group == '2' ? 'selected' : '' ?>>2</option>
                        <option value="3" <?= $class_group == '3' ? 'selected' : '' ?>>3</option>
                        <option value="4" <?= $class_group == '4' ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                Rekap Bulan <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th class="text-center text-success">Hadir</th>
                            <th class="text-center text-warning">Izin</th>
                            <th class="text-center text-info">Sakit</th>
                            <th class="text-center text-danger">Alpa</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rekapData as $i => $row): ?>
                            <?php $total = $row['Hadir'] + $row['Izin'] + $row['Sakit'] + $row['Alpa']; ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="fw-semibold"><?= esc($row['full_name']) ?></td>
                                <td class="text-muted small"><?= esc($row['nis'] ?? '-') ?></td>
                                <td class="small"><?= $row['grade'] . ' ' . ($row['major'] ?? '') . '-' . $row['class_group'] ?></td>
                                <td class="text-center"><span class="badge bg-success"><?= $row['Hadir'] ?></span></td>
                                <td class="text-center"><span class="badge bg-warning text-dark"><?= $row['Izin'] ?></span></td>
                                <td class="text-center"><span class="badge bg-info"><?= $row['Sakit'] ?></span></td>
                                <td class="text-center"><span class="badge bg-danger"><?= $row['Alpa'] ?></span></td>
                                <td class="text-center fw-semibold"><?= $total ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
