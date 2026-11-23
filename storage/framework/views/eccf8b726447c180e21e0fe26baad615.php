

<?php $__env->startSection('content'); ?>

<h3 class="mb-3">Tambah Kos Baru</h3>

<div class="card">
    <div class="card-body">
        
        <h5 class="card-title fw-semibold mb-4">Formulir Pendaftaran Kos</h5>
        <p class="card-subtitle mb-3">
            Silakan isi semua data di bawah ini untuk mendaftarkan properti kos baru Anda.
        </p>

        <form action="<?php echo e(url('/owner/kos')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="nama_kos" class="form-label">Nama Kos</label>
                    <input type="text" class="form-control" id="nama_kos" name="nama_kos" placeholder="Contoh: Kos Putra Idaman" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tipe_kos" class="form-label">Tipe Kos</label>
                    <select class="form-select" id="tipe_kos" name="tipe_kos" required>
                        <option value="pria">Pria</option>
                        <option value="wanita">Wanita</option>
                        <option value="campur">Campur</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Contoh: Jl. Merdeka No. 10, RT 01 RW 05, Kelurahan..." required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kota" class="form-label">Kota</label>
                    <input type="text" class="form-control" id="kota" name="kota" placeholder="Contoh: Jakarta Selatan" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ukuran" class="form-label">Ukuran Kamar (Opsional)</label>
                    <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Contoh: 3x4 Meter">
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="harga" class="form-label">Harga Sewa</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 2000000" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="durasi_harga" class="form-label">Durasi Harga</label>
                    <select class="form-select" id="durasi_harga" name="durasi_harga" required>
                        <option value="month">Per Bulan</option>
                        <option value="year">Per Tahun</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi Kos (Opsional)</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan tentang kos Anda, fasilitas umum, peraturan, dll..."></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="#" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan dan Lanjutkan</button>
            </div>

        </form>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/kos/create.blade.php ENDPATH**/ ?>