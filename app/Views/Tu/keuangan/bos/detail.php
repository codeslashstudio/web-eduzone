<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dana BOS - EduZone TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php
    $dana_bos = [
        'id_bos' => 1,
        'no_dana' => 'BOS-2026-T1-001',
        'jenis' => 'BOS',
        'tahun_ajaran' => '2025/2026',
        'periode' => 'Triwulan 1',
        'jumlah_diterima' => 75000000,
        'tanggal_terima' => '2026-01-15',
        'no_sk' => 'SK-012/BOS/2026',
        'file_sk' => 'sk_bos_t1.pdf',
        'keterangan' => 'Dana BOS Triwulan 1 Tahun Ajaran 2025/2026'
    ];

    $realisasi = [
        ['kategori' => 'Gaji Guru Honorer', 'jumlah' => 24000000, 'tanggal' => '2026-01-20', 'keterangan' => '8 guru honorer'],
        ['kategori' => 'Operasional Sekolah', 'jumlah' => 15000000, 'tanggal' => '2026-01-22', 'keterangan' => 'Biaya listrik, air, internet'],
        ['kategori' => 'ATK & Administrasi', 'jumlah' => 8000000, 'tanggal' => '2026-01-25', 'keterangan' => 'Kertas, tinta printer, dll'],
        ['kategori' => 'Pemeliharaan Gedung', 'jumlah' => 10000000, 'tanggal' => '2026-01-28', 'keterangan' => 'Perbaikan atap kelas'],
        ['kategori' => 'Konsumsi Kegiatan', 'jumlah' => 3000000, 'tanggal' => '2026-01-30', 'keterangan' => 'Rapat dan pelatihan'],
    ];

    $total_terpakai = array_sum(array_column($realisasi, 'jumlah'));
    $sisa_dana = $dana_bos['jumlah_diterima'] - $total_terpakai;
    $persentase = round(($total_terpakai / $dana_bos['jumlah_diterima']) * 100, 1);
    ?>

    <div class="min-h-screen p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6 no-print" data-aos="fade-down">
                <a href="<?= base_url('tu/keuangan/bos') ?>"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar BOS
                </a>
            </div>

            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white shadow-xl mb-8" data-aos="fade-up">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-hand-holding-usd text-3xl"></i>
                            </div>
                            <div>
                                <p class="text-purple-100 text-sm font-medium mb-1">Dana BOS/BOP</p>
                                <h1 class="text-3xl font-bold"><?= $dana_bos['no_dana'] ?></h1>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 bg-white/20 rounded-full text-sm font-semibold">
                                <?= $dana_bos['jenis'] ?>
                            </span>
                            <span class="px-4 py-2 bg-white/20 rounded-full text-sm font-semibold">
                                <?= $dana_bos['periode'] ?>
                            </span>
                            <span class="px-4 py-2 bg-white/20 rounded-full text-sm font-semibold">
                                <?= $dana_bos['tahun_ajaran'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-purple-100 text-sm mb-2">Dana Diterima</p>
                        <h2 class="text-4xl font-bold">Rp <?= number_format($dana_bos['jumlah_diterima'], 0, ',', '.') ?></h2>
                        <p class="text-purple-100 text-xs mt-2">
                            Terima: <?= date('d F Y', strtotime($dana_bos['tanggal_terima'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp <?= number_format($dana_bos['jumlah_diterima'], 0, ',', '.') ?></p>
                    <p class="text-sm text-gray-500">Dana Diterima</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp <?= number_format($total_terpakai, 0, ',', '.') ?></p>
                    <p class="text-sm text-gray-500">Total Terpakai</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp <?= number_format($sisa_dana, 0, ',', '.') ?></p>
                    <p class="text-sm text-gray-500">Sisa Dana</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1"><?= $persentase ?>%</p>
                    <p class="text-sm text-gray-500">Persentase Penggunaan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Progress Bar -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-purple-500 mr-3"></i>
                            Progress Penggunaan Dana
                        </h3>
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-semibold text-gray-700">
                                    Rp <?= number_format($total_terpakai, 0, ',', '.') ?> dari Rp <?= number_format($dana_bos['jumlah_diterima'], 0, ',', '.') ?>
                                </span>
                                <span class="font-bold text-purple-600"><?= $persentase ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-6">
                                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-6 rounded-full flex items-center justify-end px-2 transition-all"
                                    style="width: <?= $persentase ?>%">
                                    <span class="text-white text-xs font-bold"><?= $persentase ?>%</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Diterima</p>
                                <p class="text-sm font-bold text-purple-600">
                                    Rp <?= number_format($dana_bos['jumlah_diterima'] / 1000000, 0) ?> Jt
                                </p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Terpakai</p>
                                <p class="text-sm font-bold text-blue-600">
                                    Rp <?= number_format($total_terpakai / 1000000, 0) ?> Jt
                                </p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Sisa</p>
                                <p class="text-sm font-bold text-green-600">
                                    Rp <?= number_format($sisa_dana / 1000000, 0) ?> Jt
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Realisasi -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-purple-500 mr-3"></i>
                            Distribusi Penggunaan Dana
                        </h3>
                        <canvas id="realisasiChart"></canvas>
                    </div>

                    <!-- Daftar Realisasi -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-list text-purple-500 mr-3"></i>
                                Detail Realisasi Penggunaan
                            </h3>
                            <p class="text-sm text-gray-500 mt-1"><?= count($realisasi) ?> transaksi pengeluaran</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <?php foreach ($realisasi as $i => $r): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900"><?= $i + 1 ?></td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <?= date('d M Y', strtotime($r['tanggal'])) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    <?= $r['kategori'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700"><?= $r['keterangan'] ?></td>
                                            <td class="px-6 py-4 text-right font-bold text-blue-600">
                                                Rp <?= number_format($r['jumlah'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900">TOTAL:</td>
                                        <td class="px-6 py-4 text-right font-bold text-purple-600 text-lg">
                                            Rp <?= number_format($total_terpakai, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Info BOS -->
                    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-purple-500 mr-3"></i>
                            Informasi Dana
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nomor SK</p>
                                <p class="text-sm font-mono font-semibold text-gray-900"><?= $dana_bos['no_sk'] ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Tanggal Terima</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?= date('d F Y', strtotime($dana_bos['tanggal_terima'])) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                                <p class="text-sm text-gray-700"><?= $dana_bos['keterangan'] ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- File SK -->
                    <?php if (!empty($dana_bos['file_sk'])): ?>
                        <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-file-alt text-purple-500 mr-3"></i>
                                Dokumen SK
                            </h3>
                            <div class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg">
                                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-file-pdf text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate"><?= $dana_bos['file_sk'] ?></p>
                                    <p class="text-xs text-gray-500">Surat Keputusan</p>
                                </div>
                            </div>
                            <a href="<?= base_url('uploads/keuangan/bos/' . $dana_bos['file_sk']) ?>"
                                target="_blank"
                                class="mt-3 w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all">
                                <i class="fas fa-download mr-2"></i>
                                Download SK
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 no-print" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                            Actions
                        </h3>
                        <div class="space-y-3">
                            <button onclick="window.print()"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                                <i class="fas fa-print mr-2"></i>
                                Print Laporan
                            </button>
                            <button onclick="exportExcel()"
                                class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                                <i class="fas fa-file-excel mr-2"></i>
                                Export Excel
                            </button>
                            <a href="<?= base_url('tu/keuangan/bos/edit/' . $dana_bos['id_bos']) ?>"
                                class="w-full flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Realisasi Chart
        const ctx = document.getElementById('realisasiChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($realisasi, 'kategori')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($realisasi, 'jumlah')) ?>,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        function exportExcel() {
            const data = <?= json_encode($realisasi) ?>;
            const ws = XLSX.utils.json_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Realisasi BOS');
            XLSX.writeFile(wb, 'Realisasi_BOS_<?= $dana_bos["periode"] ?>_<?= date("Y-m-d") ?>.xlsx');
        }
    </script>
</body>

</html>