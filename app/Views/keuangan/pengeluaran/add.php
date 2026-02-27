<?php $this->extend('layout/main') ?>

<?php $this->section('sidebar_menu') ?>
<?php $role = session()->get('role') ?>
<a href="<?= base_url('dashboard/' . $role) ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-home w-5"></i><span class="sidebar-text font-semibold text-sm">Dashboard</span>
</a>
<a href="<?= base_url('siswa') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-user-graduate w-5"></i><span class="sidebar-text font-semibold text-sm">Data Siswa</span>
</a>
<a href="<?= base_url('guru') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chalkboard-teacher w-5"></i><span class="sidebar-text font-semibold text-sm">Data Guru</span>
</a>
<div>
    <button onclick="toggleSubmenu()" class="menu-item active w-full flex items-center justify-between px-4 py-3 rounded-xl">
        <div class="flex items-center space-x-3">
            <i class="fas fa-money-bill-wave w-5"></i>
            <span class="sidebar-text font-semibold text-sm">Keuangan</span>
        </div>
        <i class="fas fa-chevron-down sidebar-text text-xs rotate-180" id="submenuIcon"></i>
    </button>
            <div class="ml-4 mt-1 space-y-1 sidebar-text" id="keuanganSubmenu">
        <a href="<?= base_url('keuangan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-chart-pie w-4"></i><span>Dashboard</span>
        </a>
        <a href="<?= base_url('keuangan/pemasukan') ?>" class="menu-item flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-down w-4"></i><span>Pemasukan</span>
        </a>
        <a href="<?= base_url('keuangan/pengeluaran') ?>" class="menu-item active flex items-center space-x-2 px-3 py-2 rounded-xl text-sm">
            <i class="fas fa-arrow-up w-4"></i><span>Pengeluaran</span>
        </a>
    </div>
</div>
<a href="<?= base_url('laporan') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-chart-line w-5"></i><span class="sidebar-text font-semibold text-sm">Laporan</span>
</a>
<a href="<?= base_url('password') ?>" class="menu-item flex items-center space-x-3 px-4 py-3">
    <i class="fas fa-key w-5"></i><span class="sidebar-text font-semibold text-sm">Ubah Password</span>
</a>
<?php $this->endSection() ?>


<?php $this->section('content') ?>
<style>
    .submenu { max-height:0; overflow:hidden; transition:max-height 0.3s ease; }
    .submenu.active { max-height:300px; }
    .form-input { width:100%; padding:0.75rem 1rem; border:2px solid #e5e7eb; border-radius:0.75rem; outline:none; transition:border-color 0.2s; background:white; }
    .form-input:focus { border-color:var(--color-primary); }
</style>

<!-- Breadcrumb -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="<?= base_url('keuangan') ?>" class="hover:underline">Keuangan</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="<?= base_url('keuangan/pengeluaran') ?>" class="hover:underline">Pengeluaran</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="font-semibold" style="color:var(--color-primary)">Tambah</span>
    </div>
    <a href="<?= base_url('keuangan/pengeluaran') ?>" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold text-sm">
        <i class="fas fa-arrow-left"></i><span>Kembali</span>
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl">
    <div class="flex items-start"><i class="fas fa-exclamation-circle text-xl mr-3 mt-0.5"></i>
        <div><p class="font-bold mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<?php endif ?>

<div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
    <div class="p-6 bg-gradient-to-r from-red-600 to-orange-500">
        <h2 class="text-xl font-bold text-white flex items-center">
            <i class="fas fa-minus-circle mr-3"></i>Form Tambah Pengeluaran
        </h2>
        <p class="text-white/70 mt-1 text-sm">Catat transaksi pengeluaran keuangan sekolah</p>
    </div>

    <form action="<?= base_url('keuangan/pengeluaran/store') ?>" method="POST" enctype="multipart/form-data" class="p-8">
        <?= csrf_field() ?>

        <!-- Informasi Transaksi -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-red-500"><i class="fas fa-info-circle"></i></span>
                Informasi Transaksi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" class="form-input" required value="<?= old('tanggal_transaksi', date('Y-m-d')) ?>">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="kategori" class="form-input" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($kategori)): ?>
                            <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= old('id_kategori') == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                            <?php endforeach ?>
                        <?php else: ?>
                            <option value="gaji">Gaji</option>
                            <option value="operasional">Operasional</option>
                            <option value="pemeliharaan">Pemeliharaan</option>
                            <option value="atk">ATK</option>
                            <option value="lainnya">Lainnya</option>
                        <?php endif ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
                    <textarea name="keterangan" rows="3" class="form-input" required placeholder="Contoh: Pembayaran Gaji Guru Honorer Januari 2026"><?= old('keterangan') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tujuan/Penerima</label>
                    <input type="text" name="tujuan" class="form-input" value="<?= old('tujuan') ?>" placeholder="Contoh: CV. Maju Jaya">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" class="form-input" required value="<?= old('jumlah') ?>" placeholder="0" min="0">
                    <p class="mt-1 text-sm font-semibold text-red-600" id="terbilang"></p>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-orange-500"><i class="fas fa-credit-card"></i></span>
                Detail Pembayaran
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" class="form-input" required>
                        <option value="">-- Pilih Metode --</option>
                        <?php foreach (['Tunai','Transfer','Cek','Giro'] as $m): ?>
                        <option value="<?= $m ?>" <?= old('metode_pembayaran') === $m ? 'selected' : '' ?>><?= $m === 'Transfer' ? 'Transfer Bank' : $m ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Bukti/Referensi</label>
                    <input type="text" name="no_bukti" class="form-input" value="<?= old('no_bukti') ?>" placeholder="Contoh: KWT-001">
                </div>
            </div>
        </div>

        <!-- Sumber Dana BOS -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-purple-500"><i class="fas fa-hand-holding-usd"></i></span>
                Sumber Dana
            </h3>
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="is_from_bos" id="is_from_bos" value="1"
                        <?= old('is_from_bos') ? 'checked' : '' ?>
                        class="mt-1 w-5 h-5 text-purple-600 rounded">
                    <div class="ml-3">
                        <span class="text-sm font-semibold text-gray-900">Menggunakan Dana BOS/BOP</span>
                        <p class="text-xs text-gray-500 mt-1">Centang jika pengeluaran ini berasal dari dana BOS/BOP</p>
                    </div>
                </label>
                <div id="bosInfo" class="mt-3 hidden">
                    <p class="text-xs text-purple-700 bg-purple-100 border border-purple-300 rounded-xl p-3">
                        <i class="fas fa-info-circle mr-1"></i>Transaksi ini akan dicatat sebagai realisasi penggunaan Dana BOS/BOP
                    </p>
                </div>
            </div>
        </div>

        <!-- Upload Bukti -->
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-900 mb-5 pb-2 border-b flex items-center">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-2 text-white text-xs bg-gray-500"><i class="fas fa-paperclip"></i></span>
                Upload Bukti <span class="text-gray-400 font-normal text-sm ml-1">(Opsional)</span>
            </h3>
            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, PDF (Maks. 2MB)</p>
                <input type="file" name="file_bukti" id="file_bukti" class="hidden" accept=".png,.jpg,.jpeg,.pdf" onchange="previewFile(this)">
            </label>
            <div id="filePreview" class="mt-3 hidden">
                <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-xl">
                    <i class="fas fa-file-alt text-red-400 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900" id="fileName"></p>
                        <p class="text-xs text-gray-400" id="fileSize"></p>
                    </div>
                    <button type="button" onclick="removeFile()" class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>

        <!-- Warning -->
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-xl text-sm text-yellow-700">
            <p class="font-semibold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i>Perhatian:</p>
            <p>Pastikan jumlah sesuai bukti. Upload bukti pembayaran untuk keperluan audit.</p>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="<?= base_url('keuangan/pengeluaran') ?>" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-save mr-2"></i>Simpan Pengeluaran
            </button>
        </div>
    </form>
</div>
<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
    function toggleSubmenu() {
        document.getElementById('keuanganSubmenu').classList.toggle('active');
        document.getElementById('submenuIcon').classList.toggle('rotate-180');
    }
    function terbilang(n) {
        const b=['','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas'];
        if(n<12)return b[n]; if(n<20)return terbilang(n-10)+' Belas';
        if(n<100)return terbilang(Math.floor(n/10))+' Puluh '+terbilang(n%10);
        if(n<200)return 'Seratus '+terbilang(n-100); if(n<1000)return terbilang(Math.floor(n/100))+' Ratus '+terbilang(n%100);
        if(n<2000)return 'Seribu '+terbilang(n-1000); if(n<1e6)return terbilang(Math.floor(n/1000))+' Ribu '+terbilang(n%1000);
        if(n<1e9)return terbilang(Math.floor(n/1e6))+' Juta '+terbilang(n%1e6);
        return terbilang(Math.floor(n/1e9))+' Miliar '+terbilang(n%1e9);
    }
    document.getElementById('jumlah').addEventListener('input',function(){
        const v=parseInt(this.value)||0;
        document.getElementById('terbilang').textContent=v>0?terbilang(v)+' Rupiah':'';
    });
    document.getElementById('is_from_bos').addEventListener('change',function(){
        document.getElementById('bosInfo').classList.toggle('hidden',!this.checked);
    });
    document.getElementById('kategori').addEventListener('change',function(){
        const ket=document.querySelector('textarea[name="keterangan"]');
        if(ket.value!=='')return;
        const label=this.options[this.selectedIndex].text;
        const bln=new Date().toLocaleDateString('id-ID',{month:'long',year:'numeric'});
        if(label.includes('Gaji'))ket.value=`Pembayaran Gaji ${bln}`;
        else if(label.includes('ATK'))ket.value=`Pembelian Alat Tulis Kantor ${bln}`;
        else if(label.includes('Operasional'))ket.value=`Biaya Operasional ${bln}`;
    });
    function previewFile(input){
        if(!input.files[0])return;
        const file=input.files[0],size=(file.size/1024/1024).toFixed(2);
        if(size>2){alert('Ukuran file terlalu besar. Maksimal 2MB');input.value='';return;}
        document.getElementById('fileName').textContent=file.name;
        document.getElementById('fileSize').textContent=size+' MB';
        document.getElementById('filePreview').classList.remove('hidden');
    }
    function removeFile(){document.getElementById('file_bukti').value='';document.getElementById('filePreview').classList.add('hidden');}
</script>
<?php $this->endSection() ?>