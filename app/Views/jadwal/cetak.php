<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran - <?= esc($kelas['nama_kelas'] ?? '') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: white;
            color: #1e293b;
            font-size: 12px;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            padding: 32px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4c6ef5;
            margin-bottom: 24px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            background: #4c6ef5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            flex-shrink: 0;
        }

        .header-info h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }

        .header-info p {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 24px;
        }

        .doc-title h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
        }

        .doc-title .kelas-badge {
            display: inline-block;
            background: #4c6ef5;
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            margin-top: 6px;
        }

        .meta-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .meta-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .meta-card .label {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .meta-card .value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 3px;
        }

        /* Table per hari */
        .hari-block {
            margin-bottom: 20px;
            break-inside: avoid;
        }

        .hari-header {
            background: #4c6ef5;
            color: white;
            padding: 8px 14px;
            border-radius: 8px 8px 0 0;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hari-header .count {
            background: rgba(255,255,255,0.2);
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            margin-left: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f1f5f9;
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .time-cell {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 12px;
            white-space: nowrap;
            color: #4c6ef5;
        }

        .subject-cell {
            font-weight: 700;
            color: #1e293b;
        }

        .teacher-cell {
            color: #475569;
        }

        .room-cell {
            color: #94a3b8;
            font-size: 11px;
        }

        .durasi-badge {
            display: inline-block;
            background: #e0e7ff;
            color: #4c6ef5;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
        }

        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer .ttd {
            text-align: center;
        }

        .footer .ttd .line {
            width: 160px;
            border-bottom: 1px solid #1e293b;
            margin: 40px auto 4px;
        }

        .footer .ttd p {
            font-size: 11px;
            color: #64748b;
        }

        .footer .ttd strong {
            font-size: 12px;
            color: #1e293b;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4c6ef5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(76,110,245,0.4);
        }

        @media print {
            .print-btn { display: none !important; }
            body { background: white; }
            .page { padding: 16px; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">
    🖨️ Cetak / Simpan PDF
</button>

<div class="page">

    <!-- Header sekolah -->
    <div class="header">
        <div class="header-logo">
            <?= strtoupper(substr($sekolah['name'] ?? 'S', 0, 1)) ?>
        </div>
        <div class="header-info">
            <h1><?= esc($sekolah['name'] ?? 'Nama Sekolah') ?></h1>
            <p><?= esc($sekolah['address'] ?? '') ?>, <?= esc($sekolah['city'] ?? '') ?></p>
            <p>Telp: <?= esc($sekolah['phone'] ?? '-') ?> · <?= esc($sekolah['email'] ?? '') ?></p>
        </div>
    </div>

    <!-- Judul dokumen -->
    <div class="doc-title">
        <h2>JADWAL PELAJARAN</h2>
        <div class="kelas-badge"><?= esc($kelas['nama_kelas'] ?? '-') ?></div>
        <p style="font-size:11px;color:#94a3b8;margin-top:8px">
            Tahun Pelajaran <?= esc($kelas['academic_year'] ?? '2025/2026') ?>
        </p>
    </div>

    <!-- Meta info -->
    <div class="meta-row">
        <div class="meta-card">
            <div class="label">Kelas</div>
            <div class="value"><?= esc($kelas['nama_kelas'] ?? '-') ?></div>
        </div>
        <div class="meta-card">
            <div class="label">Wali Kelas</div>
            <div class="value"><?= esc($kelas['nama_wakel'] ?? 'Belum ditentukan') ?></div>
        </div>
        <div class="meta-card">
            <div class="label">Dicetak</div>
            <div class="value"><?= date('d M Y') ?></div>
        </div>
    </div>

    <!-- Jadwal per hari -->
    <?php
    $hariUrut = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    foreach ($hariUrut as $h):
        $sesi = $jadwalPerHari[$h] ?? [];
        if (empty($sesi)) continue;
    ?>
    <div class="hari-block">
        <div class="hari-header">
            📅 <?= $h ?>
            <span class="count"><?= count($sesi) ?> sesi</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:120px">Waktu</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th style="width:100px">Ruang</th>
                    <th style="width:70px;text-align:center">Durasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sesi as $j):
                    $start  = strtotime($j['start_time']);
                    $end    = strtotime($j['end_time']);
                    $durasi = round(($end - $start) / 60);
                ?>
                <tr>
                    <td class="time-cell">
                        <?= substr($j['start_time'], 0, 5) ?> – <?= substr($j['end_time'], 0, 5) ?>
                    </td>
                    <td class="subject-cell"><?= esc($j['subject']) ?></td>
                    <td class="teacher-cell"><?= esc($j['nama_guru'] ?? '-') ?></td>
                    <td class="room-cell"><?= esc($j['room'] ?? '-') ?></td>
                    <td style="text-align:center">
                        <span class="durasi-badge"><?= $durasi ?> mnt</span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endforeach ?>

    <!-- TTD -->
    <div class="footer">
        <div style="font-size:11px;color:#94a3b8;max-width:300px">
            <p>Dokumen ini dicetak secara otomatis dari sistem informasi sekolah.</p>
        </div>
        <div class="ttd">
            <p><?= esc($sekolah['city'] ?? 'Kota') ?>, <?= date('d F Y') ?></p>
            <p style="margin-top:4px">Kepala Sekolah</p>
            <div class="line"></div>
            <strong><?= esc($sekolah['principal_name'] ?? 'Kepala Sekolah') ?></strong><br>
            <p><?= esc($sekolah['principal_nip'] ?? '') ?></p>
        </div>
    </div>

</div>
</body>
</html>