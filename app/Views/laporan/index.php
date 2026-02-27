<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<div>
    <button onclick="toggleKeuangan()" class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-money-bill-wave w-5"></i>
            <span class="sidebar-text font-semibold text-sm">Keuangan</span>
        </div>
        <i class="fas fa-chevron-down sidebar-text text-xs" id="keuanganIcon"></i>
    </button>
    <div class="ml-4 mt-1 space-y-1 sidebar-text hidden" id="keuanganSubmenu">
        <a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <a href="<?= base_url('keuangan/pemasukan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-down w-4"></i><span>Pemasukan</span>
        </a>
        <a href="<?= base_url('keuangan/pengeluaran') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-up w-4"></i><span>Pengeluaran</span>
        </a>
    </div>
</div>
<a href="<?= base_url('laporan') ?>" class="menu-item active flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i>
    <span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<!-- Filter & Export -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6" data-aos="fade-up">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div class="flex items-end gap-4 flex-wrap">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran</label>
                <select id="filterTahun" class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:outline-none text-sm">
                    <option>2025/2026</option>
                    <option>2024/2025</option>
                    <option>2023/2024</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                <select id="filterSemester" class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:outline-none text-sm">
                    <option>Ganjil</option>
                    <option>Genap</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="exportToExcel()" class="btn-primary flex items-center space-x-2 px-5 py-2.5 rounded-xl font-semibold text-sm">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </button>
            <button onclick="window.print()" class="flex items-center space-x-2 bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-all font-semibold text-sm">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white shadow-xl stat-card" data-aos="fade-up"
         style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-school text-2xl"></i>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= $totalKelas ?? 12 ?></h3>
        <p class="text-white/80 text-sm font-medium">Total Kelas</p>
    </div>

    <div class="rounded-2xl p-6 text-white shadow-xl stat-card" data-aos="fade-up" data-aos-delay="100"
         style="background: linear-gradient(135deg, var(--color-secondary), var(--color-primary))">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-chart-line text-2xl"></i>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= number_format($rataRataKeseluruhan ?? 82.5, 1) ?></h3>
        <p class="text-white/80 text-sm font-medium">Rata-rata Keseluruhan</p>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-xl stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-trophy text-2xl"></i>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= number_format($nilaiTertinggi ?? 95.8, 1) ?></h3>
        <p class="text-white/80 text-sm font-medium">Nilai Tertinggi</p>
    </div>

    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 text-white shadow-xl stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-users text-2xl"></i>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?= number_format($totalSiswa ?? 1234) ?></h3>
        <p class="text-white/80 text-sm font-medium">Total Siswa</p>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6" data-aos="fade-up">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Laporan Per Kelas</h2>
                <p class="text-sm text-gray-400 mt-1">Data prestasi akademik per kelas</p>
            </div>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari kelas..."
                    class="pl-9 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:outline-none w-48 text-sm"
                    >
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="laporanTable">
            <thead>
                <tr style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary))" class="text-white">
                    <th class="px-6 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Kelas</th>
                    <th class="px-6 py-4 text-left text-sm font-bold">Jurusan</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Jumlah Siswa</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Rata-rata</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Tertinggi</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Terendah</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php
                $laporan = $laporan ?? [
                    ['kelas' => 'X',   'jurusan' => 'IPA 1', 'jumlah_siswa' => 36, 'rata_rata' => 85.5, 'tertinggi' => 95.8, 'terendah' => 72.3],
                    ['kelas' => 'X',   'jurusan' => 'IPA 2', 'jumlah_siswa' => 34, 'rata_rata' => 83.2, 'tertinggi' => 93.5, 'terendah' => 70.5],
                    ['kelas' => 'X',   'jurusan' => 'IPS 1', 'jumlah_siswa' => 35, 'rata_rata' => 81.8, 'tertinggi' => 91.2, 'terendah' => 68.9],
                    ['kelas' => 'X',   'jurusan' => 'IPS 2', 'jumlah_siswa' => 33, 'rata_rata' => 80.5, 'tertinggi' => 89.7, 'terendah' => 67.3],
                    ['kelas' => 'XI',  'jurusan' => 'IPA 1', 'jumlah_siswa' => 32, 'rata_rata' => 84.3, 'tertinggi' => 94.2, 'terendah' => 71.8],
                    ['kelas' => 'XI',  'jurusan' => 'IPA 2', 'jumlah_siswa' => 31, 'rata_rata' => 82.7, 'tertinggi' => 92.8, 'terendah' => 69.5],
                    ['kelas' => 'XI',  'jurusan' => 'IPS 1', 'jumlah_siswa' => 34, 'rata_rata' => 79.9, 'tertinggi' => 88.5, 'terendah' => 66.7],
                    ['kelas' => 'XI',  'jurusan' => 'IPS 2', 'jumlah_siswa' => 30, 'rata_rata' => 78.6, 'tertinggi' => 87.3, 'terendah' => 65.2],
                    ['kelas' => 'XII', 'jurusan' => 'IPA 1', 'jumlah_siswa' => 35, 'rata_rata' => 86.2, 'tertinggi' => 95.5, 'terendah' => 73.5],
                    ['kelas' => 'XII', 'jurusan' => 'IPA 2', 'jumlah_siswa' => 33, 'rata_rata' => 84.8, 'tertinggi' => 93.8, 'terendah' => 72.1],
                    ['kelas' => 'XII', 'jurusan' => 'IPS 1', 'jumlah_siswa' => 32, 'rata_rata' => 82.4, 'tertinggi' => 90.6, 'terendah' => 70.3],
                    ['kelas' => 'XII', 'jurusan' => 'IPS 2', 'jumlah_siswa' => 29, 'rata_rata' => 81.1, 'tertinggi' => 89.2, 'terendah' => 68.8],
                ];

                foreach ($laporan as $i => $l):
                    if ($l['rata_rata'] >= 85)     { $status = 'Sangat Baik';     $sc = 'bg-emerald-100 text-emerald-700'; }
                    elseif ($l['rata_rata'] >= 75)  { $status = 'Baik';            $sc = 'bg-blue-100 text-blue-700'; }
                    elseif ($l['rata_rata'] >= 65)  { $status = 'Cukup';           $sc = 'bg-yellow-100 text-yellow-700'; }
                    else                            { $status = 'Perlu Perbaikan'; $sc = 'bg-red-100 text-red-700'; }
                    $slug = $l['kelas'] . '-' . str_replace(' ', '-', $l['jurusan']);
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-500"><?= $i + 1 ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900"><?= esc($l['kelas']) ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800"><?= esc($l['jurusan']) ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold"><?= $l['jumlah_siswa'] ?> Siswa</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold" style="color: var(--color-primary)"><?= number_format($l['rata_rata'], 1) ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold text-emerald-600"><?= number_format($l['tertinggi'], 1) ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold text-orange-500"><?= number_format($l['terendah'], 1) ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $sc ?>"><?= $status ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('laporan/detail/' . $slug) ?>"
                           class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                           style="background: rgba(var(--color-primary-rgb), 0.1); color: var(--color-primary)">
                            <i class="fas fa-eye mr-1.5"></i>Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        <p class="text-sm text-gray-500">Total <span class="font-bold text-gray-900"><?= count($laporan) ?></span> kelas</p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-3" style="color: var(--color-primary)"></i>
            Rata-rata Nilai Per Kelas
        </h3>
        <canvas id="barChart"></canvas>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-line mr-3" style="color: var(--color-secondary)"></i>
            Trend Nilai Tertinggi & Terendah
        </h3>
        <canvas id="lineChart"></canvas>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-lg p-6 mb-6" data-aos="fade-up">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
        <i class="fas fa-chart-pie mr-3 text-indigo-500"></i>
        Distribusi Status Kelas
    </h3>
    <div class="max-w-sm mx-auto">
        <canvas id="pieChart"></canvas>
    </div>
</div>

<?php $this->endSection() ?>


<?php $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function toggleKeuangan() {
        const el = document.getElementById('keuanganSubmenu');
        el.classList.toggle('hidden');
        document.getElementById('keuanganIcon').classList.toggle('rotate-180');
    }

    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Chart colors dari CSS variables
    const rootStyle = getComputedStyle(document.documentElement);
    const colorPrimary   = rootStyle.getPropertyValue('--color-primary').trim()   || '#667eea';
    const colorSecondary = rootStyle.getPropertyValue('--color-secondary').trim() || '#764ba2';

    const labels    = ['X IPA 1','X IPA 2','X IPS 1','X IPS 2','XI IPA 1','XI IPA 2','XI IPS 1','XI IPS 2','XII IPA 1','XII IPA 2','XII IPS 1','XII IPS 2'];
    const rataRata  = [85.5, 83.2, 81.8, 80.5, 84.3, 82.7, 79.9, 78.6, 86.2, 84.8, 82.4, 81.1];
    const tertinggi = [95.8, 93.5, 91.2, 89.7, 94.2, 92.8, 88.5, 87.3, 95.5, 93.8, 90.6, 89.2];
    const terendah  = [72.3, 70.5, 68.9, 67.3, 71.8, 69.5, 66.7, 65.2, 73.5, 72.1, 70.3, 68.8];

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: rataRata,
                backgroundColor: colorPrimary + '99',
                borderColor: colorPrimary,
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: false, min: 60, max: 100 } }
        }
    });

    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Nilai Tertinggi',
                    data: tertinggi,
                    borderColor: colorPrimary,
                    backgroundColor: colorPrimary + '22',
                    tension: 0.4, fill: true
                },
                {
                    label: 'Nilai Terendah',
                    data: terendah,
                    borderColor: '#f97316',
                    backgroundColor: '#f9731622',
                    tension: 0.4, fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { min: 60, max: 100 } }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Sangat Baik', 'Baik', 'Cukup', 'Perlu Perbaikan'],
            datasets: [{
                data: [4, 6, 2, 0],
                backgroundColor: [colorPrimary + 'cc', colorSecondary + 'cc', '#fbbf24cc', '#ef4444cc'],
                borderColor:     [colorPrimary,         colorSecondary,         '#fbbf24',   '#ef4444'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Export Excel
    function exportToExcel() {
        const wb = XLSX.utils.table_to_book(document.getElementById('laporanTable'), { sheet: 'Laporan Akademik' });
        XLSX.writeFile(wb, 'Laporan_Akademik_' + new Date().toISOString().slice(0, 10) + '.xlsx');
    }
</script>

<style media="print">
    aside, header, footer, button, .btn-primary { display: none !important; }
    #main-content { margin-left: 0 !important; }
</style>
<?php $this->endSection() ?>