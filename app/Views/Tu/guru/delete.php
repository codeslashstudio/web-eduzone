<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Guru - EduZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .delete-animation {
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-10px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(10px);
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6 rounded-t-xl">
                <div class="flex items-center justify-between text-white">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-300"></i>
                        Konfirmasi Hapus
                    </h2>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-all">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-8">
                <!-- Warning Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center delete-animation">
                        <i class="fas fa-trash-alt text-red-500 text-4xl"></i>
                    </div>
                </div>

                <!-- Guru Info -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <div class="flex items-center space-x-4">
                        <?php if (!empty($guru['foto'])): ?>
                            <img src="<?= base_url('uploads/guru/' . $guru['foto']) ?>"
                                alt="Foto Guru"
                                class="w-16 h-16 rounded-full object-cover border-2 border-red-200">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                <?= strtoupper(substr($guru['nama'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900"><?= esc($guru['nama']) ?></h3>
                            <p class="text-sm text-gray-600">NIP: <?= esc($guru['nip']) ?></p>
                            <p class="text-sm text-gray-600"><?= esc($guru['jabatan']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800 mb-1">Perhatian!</p>
                            <p class="text-sm text-yellow-700">
                                Data guru akan dinonaktifkan dan tidak akan ditampilkan dalam daftar.
                                Data masih dapat dipulihkan oleh administrator sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Text -->
                <div class="text-center mb-6">
                    <p class="text-gray-700 font-semibold text-lg mb-2">
                        Apakah Anda yakin ingin menghapus guru ini?
                    </p>
                    <p class="text-gray-500 text-sm">
                        Tindakan ini akan menonaktifkan akun guru dari sistem.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-center space-x-4">
                    <button onclick="closeModal()"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <form action="<?= base_url('kepsek/guru/delete/' . $guru['idguru']) ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                            <i class="fas fa-trash mr-2"></i>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content (when accessed directly) -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 p-8 text-center">
                <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-500 text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Hapus Data Guru</h1>
                <p class="text-red-100">Konfirmasi penghapusan data guru</p>
            </div>

            <div class="p-8">
                <!-- Guru Info Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border-2 border-gray-200">
                    <div class="flex items-center space-x-6 mb-6">
                        <?php if (!empty($guru['foto'])): ?>
                            <img src="<?= base_url('uploads/guru/' . $guru['foto']) ?>"
                                alt="Foto Guru"
                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-lg">
                                <?= strtoupper(substr($guru['nama'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1"><?= esc($guru['nama']) ?></h2>
                            <p class="text-gray-600 font-semibold">NIP: <?= esc($guru['nip']) ?></p>
                            <p class="text-gray-600"><?= esc($guru['jabatan']) ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Status Kepegawaian</p>
                            <p class="font-semibold text-gray-900"><?= esc($guru['status_kepegawaian']) ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Pendidikan</p>
                            <p class="font-semibold text-gray-900"><?= esc($guru['pendidikan_terakhir']) ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">No. HP</p>
                            <p class="font-semibold text-gray-900"><?= esc($guru['no_hp']) ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="font-semibold text-gray-900 text-sm"><?= !empty($guru['email']) ? esc($guru['email']) : '-' ?></p>
                        </div>
                    </div>
                </div>

                <!-- Warning Messages -->
                <div class="space-y-4 mb-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 mb-1">Peringatan!</p>
                                <p class="text-sm text-yellow-700">
                                    Data guru akan dinonaktifkan dari sistem. Guru tidak akan dapat login dan tidak akan muncul dalam daftar aktif.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-semibold text-blue-800 mb-1">Informasi</p>
                                <p class="text-sm text-blue-700">
                                    Data tidak akan dihapus secara permanen dan masih dapat dipulihkan oleh administrator sistem jika diperlukan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Question -->
                <div class="text-center mb-8">
                    <p class="text-xl font-bold text-gray-900 mb-2">
                        Apakah Anda yakin ingin menghapus data guru ini?
                    </p>
                    <p class="text-gray-600">
                        Pastikan keputusan Anda sudah tepat sebelum melanjutkan.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-center space-x-4">
                    <a href="<?= base_url('kepsek/guru') ?>"
                        class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <a href="<?= base_url('kepsek/guru/detail/' . $guru['idguru']) ?>"
                        class="px-8 py-4 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-all">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <form action="<?= base_url('kepsek/guru/delete/' . $guru['idguru']) ?>" method="POST" class="inline" onsubmit="return confirm('Konfirmasi terakhir: Anda yakin ingin menghapus?')">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-semibold hover:shadow-xl transition-all">
                            <i class="fas fa-trash mr-2"></i>
                            Ya, Hapus Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal Functions
        function openModal() {
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>

</html>