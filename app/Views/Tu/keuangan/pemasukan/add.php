<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pemasukan - EduZone TU</title>
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
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl mb-4 shadow-xl">
                    <i class="fas fa-arrow-down text-white text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Pemasukan</h1>
                <p class="text-gray-600">Catat transaksi pemasukan keuangan sekolah</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Form Pemasukan Baru
                    </h2>
                </div>

                <form action="<?= base_url('tu/keuangan/pemasukan/store') ?>" method="POST" enctype="multipart/form-data" class="p-8">
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
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500">
                            <i class="fas fa-info-circle text-green-500 mr-2"></i>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="id_kategori" id="kategori"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php if (isset($kategori)): ?>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k['id_kategori_pemasukan'] ?>" <?= old('id_kategori') == $k['id_kategori_pemasukan'] ? 'selected' : '' ?>>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Contoh: Pembayaran SPP Siswa Kelas X Januari 2026"
                                    required><?= old('keterangan') ?></textarea>
                            </div>

                            <!-- Sumber Dana -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Sumber Dana
                                </label>
                                <input type="text" name="sumber"
                                    value="<?= old('sumber') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Contoh: Siswa Kelas X, Pemerintah, Alumni">
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jumlah" id="jumlah"
                                    value="<?= old('jumlah') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    required>
                                <p class="mt-2 text-sm text-green-600 font-semibold" id="terbilang"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Detail Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500">
                            <i class="fas fa-credit-card text-green-500 mr-2"></i>
                            Detail Pembayaran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Metode Pembayaran -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="metode_pembayaran"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Contoh: TF-2026012345, KWT-001">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Upload Bukti -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500">
                            <i class="fas fa-paperclip text-green-500 mr-2"></i>
                            Upload Bukti (Opsional)
                        </h3>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                File Bukti Transaksi
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
                                <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-file-alt text-green-500 text-2xl mr-3"></i>
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
                        <a href="<?= base_url('tu/keuangan/pemasukan') ?>"
                            class="flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pemasukan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 mb-1">Informasi:</p>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Pastikan data yang diinput sudah benar sebelum menyimpan</li>
                            <li>• Upload bukti transaksi untuk keperluan audit</li>
                            <li>• Transaksi akan otomatis terverifikasi setelah disimpan</li>
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

        // Auto-fill keterangan based on kategori
        document.getElementById('kategori').addEventListener('change', function() {
            const kategori = this.options[this.selectedIndex].text;
            const keteranganField = document.querySelector('textarea[name="keterangan"]');

            if (keteranganField.value === '') {
                const bulan = new Date().toLocaleDateString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });

                if (kategori.includes('SPP')) {
                    keteranganField.value = `Pembayaran SPP ${bulan}`;
                } else if (kategori.includes('BOS')) {
                    keteranganField.value = `Penerimaan Dana BOS ${bulan}`;
                } else if (kategori.includes('Donasi')) {
                    keteranganField.value = `Donasi untuk sekolah`;
                }
            }
        });
    </script>
</body>

</html>