

<?php $__env->startSection('content'); ?>

<!-- Judul Halaman -->
<h3 class="mb-3">Tambah Kamar Baru</h3>

<!-- Menampilkan pesan error jika ada -->
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Kartu Utama untuk Form -->
<div class="card">
    <div class="card-body">
        
        <h5 class="card-title fw-semibold mb-4">Formulir Kamar Baru</h5>
        <p class="card-subtitle mb-3">
            Pilih kos terlebih dahulu, lalu masukkan detail kamar baru Anda.
        </p>

        <form action="<?php echo e(url('/owner/kamar')); ?>" method="POST">
            <?php echo csrf_field(); ?> <!-- Token Keamanan WAJIB -->

            <!-- Baris 1: Pilih Kos (PENTING) -->
            <div class="mb-3">
                <label for="kos_id" class="form-label">Pilih Properti Kos</label>
                <select class="form-select" id="kos_id" name="kos_id" required>
                    <option value="" disabled selected>-- Pilih Kos yang akan ditambahkan kamar --</option>
                    
                    <!-- ==== INI BAGIAN DINAMIS BARU ==== -->
                    <?php $__currentLoopData = $daftar_kos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kos->id); ?>"><?php echo e($kos->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!-- ==== AKHIR BAGIAN DINAMIS ==== -->

                </select>
            </div>

            <!-- Baris 2: Nomor Kamar -->
            <div class="mb-3">
                <label for="room_number" class="form-label">Nomor Kamar</label>
                <input type="text" class="form-control" id="room_number" name="room_number" placeholder="Contoh: A103 atau Kamar 05" required>
            </div>

            <!-- Baris 3: Status Ketersediaan -->
            <div class="mb-3">
                <label for="is_available" class="form-label">Status Ketersediaan</label>
                <select class="form-select" id="is_available" name="is_available" required>
                    <option value="1">Tersedia (Kosong)</option>
                    <option value="0">Tidak Tersedia (Sudah Terisi)</option>
                </select>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(url('/owner/kamar')); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kamar</button>
            </div>

        </form>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/kamar/create.blade.php ENDPATH**/ ?>