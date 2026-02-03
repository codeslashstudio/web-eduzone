<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dana BOS/BOP - EduZone TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Sidebar (sama seperti dashboard) -->
    <div class="min-h-screen p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8" data-aos="fade-down">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dana BOS/BOP</h1>
                        <p class="text-gray-600">Monitoring dan Realisasi Dana Bantuan Operasional Sekolah</p>
                    </div>
                    <a href="<?= base_url('tu/keuangan/bos/add') ?>"
                        class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all">
                        <i class="fas fa-plus"></i>
                        <span class="font-semibold">Tambah Penerimaan Dana</span>
                    </a>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <div class="flex items-center space-x-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran</label>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option>2025/2026</option>
                            <option>2024/2025</option>
                            <option>2023/2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Dana</label>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option>Semua</option>
                            <option>BOS</option>
                            <option>BOP</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 300 Jt</h3>
                    <p class="text-purple-100 text-sm font-medium">Total Dana Diterima</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 225 Jt</h3>
                    <p class="text-blue-100 text-sm font-medium">Total Terpakai</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">Rp 75 Jt</h3>
                    <p class="text-green-100 text-sm font-medium">Sisa Dana</p>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">75%</h3>
                    <p class="text-orange-100 text-sm font-medium">Persentase Penggunaan</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Realisasi Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-purple-500 mr-3"></i>
                        Realisasi Dana Per Kategori
                    </h3>
                    <canvas id="realisasiChart"></canvas>
                </div>

                <!-- Timeline Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-line text-blue-500 mr-3"></i>
                        Timeline Penerimaan & Penggunaan
                    </h3>
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            <!-- Dana BOS List -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8" data-aos="fade-up">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Penerimaan Dana BOS/BOP</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar penerimaan dana per periode</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Periode</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Jenis</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tanggal Terima</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Jumlah Diterima</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Terpakai</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Progress</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $dana_bos = [
                                ['id' => 1, 'periode' => 'Triwulan 1', 'jenis' => 'BOS', 'tanggal' => '2026-01-15', 'diterima' => 75000000, 'terpakai' => 60000000],
                                ['id' => 2, 'periode' => 'Triwulan 2', 'jenis' => 'BOS', 'tanggal' => '2026-01-20', 'diterima' => 75000000, 'terpakai' => 55000000],
                                ['id' => 3, 'periode' => 'Triwulan 3', 'jenis' => 'BOS', 'tanggal' => '2026-01-25', 'diterima' => 75000000, 'terpakai' => 60000000],
                                ['id' => 4, 'periode' => 'Triwulan 4', 'jenis' => 'BOS', 'tanggal' => '2026-01-28', 'diterima' => 75000000, 'terpakai' => 50000000],
                            ];

                            foreach ($dana_bos as $index => $bos):
                                $sisa = $bos['diterima'] - $bos['terpakai'];
                                $persen = round(($bos['terpakai'] / $bos['diterima']) * 100);
                                $colorClass = $persen > 90 ? 'bg-red-500' : ($persen > 75 ? 'bg-yellow-500' : 'bg-green-500');
                            ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= $index + 1 ?></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900"><?= $bos['periode'] ?></p>
                                        <p class="text-xs text-gray-500">Tahun Ajaran 2025/2026</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <?= $bos['jenis'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($bos['tanggal'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <p class="font-bold text-purple-600">Rp <?= number_format($bos['diterima'], 0, ',', '.') ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <p class="font-bold text-blue-600">Rp <?= number_format($bos['terpakai'], 0, ',', '.') ?></p>
                                        <p class="text-xs text-gray-500">Sisa: Rp <?= number_format($sisa, 0, ',', '.') ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm font-bold text-gray-900 mb-2"><?= $persen ?>%</span>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="<?= $colorClass ?> h-2.5 rounded-full transition-all" style="width: <?= $persen ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('tu/keuangan/bos/detail/' . $bos['id']) ?>"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span class="text-xs font-semibold">Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Realisasi Penggunaan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Rincian Penggunaan Dana BOS</h2>
                    <p class="text-sm text-gray-500 mt-1">Detail realisasi penggunaan dana per kategori</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <?php
                        $penggunaan = [
                            ['kategori' => 'Gaji Guru Honorer', 'jumlah' => 96000000, 'persen' => 42.7, 'color' => 'blue'],
                            ['kategori' => 'Operasional Sekolah', 'jumlah' => 56250000, 'persen' => 25, 'color' => 'green'],
                            ['kategori' => 'Pemeliharaan Gedung', 'jumlah' => 33750000, 'persen' => 15, 'color' => 'yellow'],
                            ['kategori' => 'ATK & Administrasi', 'jumlah' => 22500000, 'persen' => 10, 'color' => 'purple'],
                            ['kategori' => 'Lain-lain', 'jumlah' => 16500000, 'persen' => 7.3, 'color' => 'gray'],
                        ];

                        foreach ($penggunaan as $item):
                            $colorClasses = [
                                'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'light' => 'bg-blue-50'],
                                'green' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'light' => 'bg-green-50'],
                                'yellow' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'light' => 'bg-yellow-50'],
                                'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'light' => 'bg-purple-50'],
                                'gray' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-600', 'light' => 'bg-gray-50'],
                            ];
                            $colors = $colorClasses[$item['color']];
                        ?>
                            <div class="<?= $colors['light'] ?> rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 <?= $colors['bg'] ?> rounded-full"></div>
                                        <span class="font-semibold text-gray-900"><?= $item['kategori'] ?></span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold <?= $colors['text'] ?>">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></p>
                                        <p class="text-xs text-gray-500"><?= $item['persen'] ?>%</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="<?= $colors['bg'] ?> h-2 rounded-full transition-all" style="width: <?= $item['persen'] ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Realisasi Pie Chart
        const realisasiCtx = document.getElementById('realisasiChart').getContext('2d');
        new Chart(realisasiCtx, {
            type: 'doughnut',
            data: {
                labels: ['Gaji Guru', 'Operasional', 'Pemeliharaan', 'ATK', 'Lain-lain'],
                datasets: [{
                    data: [42.7, 25, 15, 10, 7.3],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ]
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
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });

        // Timeline Bar Chart
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        new Chart(timelineCtx, {
            type: 'bar',
            data: {
                labels: ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'],
                datasets: [{
                        label: 'Dana Diterima',
                        data: [75, 75, 75, 75],
                        backgroundColor: 'rgba(168, 85, 247, 0.6)',
                        borderColor: 'rgba(168, 85, 247, 1)',
                        borderWidth: 2
                    },
                    {
                        label: 'Dana Terpakai',
                        data: [60, 55, 60, 50],
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' Jt';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>