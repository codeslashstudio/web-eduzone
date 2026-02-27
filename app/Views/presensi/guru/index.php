<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📋 Presensi Guru</h4>
        <div class="d-flex gap-2">
            <a href="/presensi-guru/input?date=<?= $date ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Input Presensi
            </a>
            <a href="/presensi-guru/rekap" class="btn btn-outline-secondary">
                <i class="bi bi-bar-chart"></i> Rekap Bulanan
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Tanggal -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="/presensi-guru" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="<?= $date ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success text-white shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="fs-2 fw-bold"><?= $stats['Hadir'] ?></div>
                    <div class="small">Hadir</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning text-dark shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="fs-2 fw-bold"><?= $stats['Izin'] ?></div>
                    <div class="small">Izin</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-info text-white shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="fs-2 fw-bold"><?= $stats['Sakit'] ?></div>
                    <div class="small">Sakit</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-danger text-white shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="fs-2 fw-bold"><?= $stats['Alpa'] ?></div>
                    <div class="small">Alpa</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Presensi -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                Data Presensi - <?= date('d F Y', strtotime($date)) ?>
                <span class="badge bg-secondary ms-2"><?= count($attendances) ?> data</span>
                <?php if ($stats['belum_absen'] > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?= $stats['belum_absen'] ?> belum absen</span>
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama Guru</th>
                            <th>NIP</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attendances)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data presensi untuk tanggal ini
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($attendances as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-semibold"><?= esc($row['full_name']) ?></td>
                                    <td class="text-muted small"><?= esc($row['nip'] ?? '-') ?></td>
                                    <td><?= $row['check_in'] ? date('H:i', strtotime($row['check_in'])) : '-' ?></td>
                                    <td><?= $row['check_out'] ? date('H:i', strtotime($row['check_out'])) : '-' ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match($row['status']) {
                                            'Hadir' => 'bg-success',
                                            'Izin'  => 'bg-warning text-dark',
                                            'Sakit' => 'bg-info text-white',
                                            'Alpa'  => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span>
                                    </td>
                                    <td class="small text-muted"><?= esc($row['notes'] ?? '-') ?></td>
                                    <td>
                                        <a href="/presensi-guru/edit/<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/presensi-guru/delete/<?= $row['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Hapus data presensi ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
