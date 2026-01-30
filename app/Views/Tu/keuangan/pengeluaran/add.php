<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran - EduZone TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-8">
        <div class="max-w-4xl w-full">
            <!-- Header -->
            <div class="mb-8 text-center" data-aos="fade-down">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-orange-500 rounded-2xl mb-4 shadow-xl">
                    <i class="fas fa-arrow-up text-white text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Pengeluaran</h1>
                <p class="text-gray-600">Catat transaksi pengeluaran keuangan sekolah</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up">
                <div class="bg-gradient-to-r from-red-600 to-orange-600 p-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-minus-circle mr-3"></i>
                        Form Pengeluaran Baru
                    </h2>
                </div>

                <form action="<?= base_url('tu/keuangan/pengeluaran/store') ?>" method="POST" enctype="multipart/form-data" class="p-8">
                    <?= csrf_field() ?>

                    <!-- Error Messages -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-red-800 mb-2">Terdapat kesalahan:</p>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Section: Informasi Transaksi -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">
                            <i class="fas fa-info-circle text-red-500 mr-2"></i>
                            Informasi Transaksi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Transaksi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_transaksi"
                                    value="<?= old('tanggal_transaksi', date('Y-m-d')) ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    required>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="id_kategori" id="kategori"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php if (isset($kategori)): ?>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k['id_kategori_pengeluaran'] ?>" <?= old('id_kategori') == $k['id_kategori_pengeluaran'] ? 'selected' : '' ?>>
                                                <?= esc($k['nama_kategori']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Keterangan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="keterangan" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    placeholder="Contoh: Pembayaran Gaji Guru Honorer Januari 2026"
                                    required><?= old('keterangan') ?></textarea>
                            </div>

                            <!-- Tujuan/Penerima -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tujuan/Penerima
                                </label>
                                <input type="text" name="tujuan"
                                    value="<?= old('tujuan') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    placeholder="Contoh: CV. Maju Jaya, Guru Honorer">
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jumlah" id="jumlah"
                                    value="<?= old('jumlah') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    required>
                                <p class="mt-2 text-sm text-red-600 font-semibold" id="terbilang"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Detail Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">
                            <i class="fas fa-credit-card text-red-500 mr-2"></i>
                            Detail Pembayaran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Metode Pembayaran -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="metode_pembayaran"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="Tunai" <?= old('metode_pembayaran') == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                                    <option value="Transfer" <?= old('metode_pembayaran') == 'Transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                    <option value="Cek" <?= old('metode_pembayaran') == 'Cek' ? 'selected' : '' ?>>Cek</option>
                                    <option value="Giro" <?= old('metode_pembayaran') == 'Giro' ? 'selected' : '' ?>>Giro</option>
                                </select>
                            </div>

                            <!-- Nomor Bukti -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Bukti/Referensi
                                </label>
                                <input type="text" name="no_bukti"
                                    value="<?= old('no_bukti') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    placeholder="Contoh: TF-2026012345, KWT-001">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Sumber Dana -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">
                            <i class="fas fa-hand-holding-usd text-red-500 mr-2"></i>
                            Sumber Dana
                        </h3>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" name="is_from_bos" id="is_from_bos" value="1"
                                    <?= old('is_from_bos') ? 'checked' : '' ?>
                                    class="mt-1 w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">Menggunakan Dana BOS/BOP</span>
                                    <p class="text-xs text-gray-600 mt-1">Centang jika pengeluaran ini berasal dari dana BOS/BOP</p>
                                </div>
                            </label>
                            <div id="bosInfo" class="mt-4 hidden">
                                <div class="bg-purple-100 border border-purple-300 rounded-lg p-3">
                                    <p class="text-xs text-purple-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Transaksi ini akan dicatat sebagai realisasi penggunaan Dana BOS/BOP
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Upload Bukti -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">
                            <i class="fas fa-paperclip text-red-500 mr-2"></i>
                            Upload Bukti (Opsional)
                        </h3>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                File Bukti Pembayaran
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, PDF (Max. 2MB)</p>
                                    </div>
                                    <input type="file" name="file_bukti" id="file_bukti" class="hidden" accept=".png,.jpg,.jpeg,.pdf" onchange="previewFile(this)">
                                </label>
                            </div>
                            <div id="filePreview" class="mt-4 hidden">
                                <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <i class="fas fa-file-alt text-red-500 text-2xl mr-3"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900" id="fileName"></p>
                                        <p class="text-xs text-gray-500" id="fileSize"></p>
                                    </div>
                                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="<?= base_url('tu/keuangan/pengeluaran') ?>"
                            class="flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>

            <!-- Warning Card -->
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 mb-1">Perhatian:</p>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• Pastikan jumlah pengeluaran sudah sesuai dengan bukti</li>
                            <li>• Upload bukti pembayaran untuk keperluan audit</li>
                            <li>• Pengeluaran dari Dana BOS akan dicatat dalam realisasi BOS</li>
                            <li>• Transaksi akan langsung tercatat sebagai "Paid" (sudah dibayar)</li>
                        </ul>
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

        // Terbilang rupiah
        const terbilang = (angka) => {
            const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

            if (angka < 12) return bilangan[angka];
            if (angka < 20) return terbilang(angka - 10) + ' Belas';
            if (angka < 100) return terbilang(Math.floor(angka / 10)) + ' Puluh ' + terbilang(angka % 10);
            if (angka < 200) return 'Seratus ' + terbilang(angka - 100);
            if (angka < 1000) return terbilang(Math.floor(angka / 100)) + ' Ratus ' + terbilang(angka % 100);
            if (angka < 2000) return 'Seribu ' + terbilang(angka - 1000);
            if (angka < 1000000) return terbilang(Math.floor(angka / 1000)) + ' Ribu ' + terbilang(angka % 1000);
            if (angka < 1000000000) return terbilang(Math.floor(angka / 1000000)) + ' Juta ' + terbilang(angka % 1000000);
            return terbilang(Math.floor(angka / 1000000000)) + ' Miliar ' + terbilang(angka % 1000000000);
        };

        document.getElementById('jumlah').addEventListener('input', function() {
            const nilai = parseInt(this.value) || 0;
            const hasil = nilai > 0 ? terbilang(nilai) + ' Rupiah' : '';
            document.getElementById('terbilang').textContent = hasil;
        });

        // BOS checkbox toggle
        document.getElementById('is_from_bos').addEventListener('change', function() {
            document.getElementById('bosInfo').classList.toggle('hidden', !this.checked);
        });

        // File preview
        function previewFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                if (fileSize > 2) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    input.value = '';
                    return;
                }

                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = fileSize + ' MB';
                document.getElementById('filePreview').classList.remove('hidden');
            }
        }

        function removeFile() {
            document.getElementById('file_bukti').value = '';
            document.getElementById('filePreview').classList.add('hidden');
        }

        // Auto-fill keterangan
        document.getElementById('kategori').addEventListener('change', function() {
            const kategori = this.options[this.selectedIndex].text;
            const keteranganField = document.querySelector('textarea[name="keterangan"]');

            if (keteranganField.value === '') {
                const bulan = new Date().toLocaleDateString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });

                if (kategori.includes('Gaji')) {
                    keteranganField.value = `Pembayaran Gaji ${bulan}`;
                } else if (kategori.includes('ATK')) {
                    keteranganField.value = `Pembelian Alat Tulis Kantor ${bulan}`;
                } else if (kategori.includes('Operasional')) {
                    keteranganField.value = `Biaya Operasional ${bulan}`;
                }
            }
        });
    </script>
</body>

</html>