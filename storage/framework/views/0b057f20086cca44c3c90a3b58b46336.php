

<?php $__env->startSection('content'); ?>

<!-- Judul Halaman dan Tombol Aksi -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Galeri Foto Kos</h3>
        <!-- Judul Dinamis -->
        <p class="card-subtitle"><?php echo e($kos->name); ?></p> 
    </div>
    <a href="<?php echo e(url('/owner/kos-saya')); ?>" class="btn btn-secondary">
        &lt; Kembali ke Daftar Kos
    </a>
</div>

<div class="row">
    <!-- Kolom Kiri: Form Upload Foto Baru -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Upload Foto Baru</h5>
                
                <p class="card-subtitle mb-3">
                    Pilih satu atau beberapa foto untuk di-upload ke galeri kos ini.
                </p>
                
                <!-- ==== BLOK PESAN ERROR & SUKSES ==== -->
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if(session('success')): ?>
                     <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                     </div>
                <?php endif; ?>
                <!-- ==== AKHIR BLOK PESAN ==== -->

                <!-- Form Upload - Diperbaiki -->
                <form action="<?php echo e(url('/owner/kos/' . $kos->id . '/foto')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> 
 
                    <div class="mb-3">
                        <label for="foto" class="form-label">Pilih Foto (Bisa lebih dari satu)</label>
                        <input class="form-control" type="file" id="foto" name="foto[]" multiple required>
                    </div>

                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption (Opsional)</label>
                        <input type="text" class="form-control" id="caption" name="caption" placeholder="Contoh: Tampilan depan kos">
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Foto</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Galeri Foto yang Sudah Ada -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Galeri Foto Saat Ini</h5>
                <div class="row">

                    <!-- Loop Dinamis (FORELSE) -->
                    <?php $__empty_1 = true; $__currentLoopData = $fotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo e(asset($foto->url)); ?>" class="card-img-top" alt="<?php echo e($foto->caption ?? 'Foto Kos'); ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-2">
                                <p class="card-text small mb-1"><?php echo e($foto->caption ?? 'Tanpa Caption'); ?></p>

                                <!-- Tombol "Jadikan Utama" (SUDAH DIPERBAIKI) -->
                                <?php if($foto->is_primary): ?>
                                    <button class="btn btn-sm btn-success w-100 mb-1" disabled>Foto Utama</button>
                                <?php else: ?>
                                    <form action="<?php echo e(url('/owner/foto/' . $foto->id . '/set-primary')); ?>" method="POST" style="display: block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-success w-100 mb-1">Jadikan Utama</button>
                                    </form>
                                <?php endif; ?>

                                <!-- Tombol Hapus (SUDAH DIPERBAIKI) -->
                                <form action="<?php echo e(url('/owner/foto/' . $foto->id)); ?>" method="POST" style="display: block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Yakin ingin menghapus foto ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!-- Ini akan tampil jika tidak ada foto sama sekali di database -->
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada foto yang di-upload untuk kos ini.</p>
                    </div>
                    <?php endif; ?>
                    <!-- AKHIR FORELSE -->

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/foto/index.blade.php ENDPATH**/ ?>