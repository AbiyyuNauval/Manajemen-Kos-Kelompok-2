

<?php $__env->startSection('content'); ?>

<style>
    /* Styling Tier SSSS untuk Kos Card */
    .filter-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .form-control-search { border: 1px solid #eef1f6; background-color: #f8f9fa; padding-left: 2.5rem; border-radius: 10px; }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a1aab5; }
    
    .kos-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        background: #fff;
    }
    .kos-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .kos-img-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    .kos-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .kos-card:hover .kos-img {
        transform: scale(1.05);
    }
    .badge-type {
        position: absolute;
        top: 15px;
        left: 15px;
        backdrop-filter: blur(5px);
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-weight: 600;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.75rem;
    }
    .action-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        opacity: 0;
        transition: all 0.3s ease;
    }
    .kos-card:hover .action-overlay {
        opacity: 1;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Properti Saya</h3>
        <p class="text-muted mb-0">Kelola daftar kos yang Anda sewakan.</p>
    </div>
    <div>
        <a href="<?php echo e(url('/owner/kos/create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="ti ti-plus me-2"></i>Tambah Properti
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="ti ti-circle-check fs-5 me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card filter-card mb-4">
    <div class="card-body p-4">
        <form action="<?php echo e(url('/owner/kos-saya')); ?>" method="GET">
            <div class="row g-3 align-items-center">
                <div class="col-lg-10">
                    <div class="position-relative">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-search py-2" 
                               placeholder="Cari nama kos, kota, atau alamat..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-dark w-100 py-2">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $semua_kos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-lg-4 col-md-6">
        <div class="card kos-card shadow-sm">
            <div class="kos-img-wrapper">
                <img src="<?php echo e($kos->primaryPhoto ? asset('storage/'.$kos->primaryPhoto->url) : 'https://placehold.co/600x400?text=No+Image'); ?>" 
                     class="kos-img" alt="<?php echo e($kos->name); ?>">
                
                <span class="badge-type shadow-sm">
                    <?php if($kos->kos_type == 'pria'): ?> 👨 Putra
                    <?php elseif($kos->kos_type == 'wanita'): ?> 👩 Putri
                    <?php else: ?> 👫 Campur <?php endif; ?>
                </span>

                <div class="action-overlay">
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(url('/owner/kos/'.$kos->id.'/edit')); ?>" class="btn btn-light btn-sm shadow-sm text-warning" title="Edit">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form action="<?php echo e(url('/owner/kos/'.$kos->id)); ?>" method="POST" onsubmit="return confirm('Hapus kos ini? Semua kamar & booking terkait akan terhapus!')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-light btn-sm shadow-sm text-danger" title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold text-dark mb-0"><?php echo e($kos->name); ?></h5>
                    <div class="text-end">
                        <span class="fw-bold text-primary">Rp <?php echo e(number_format($kos->price, 0, ',', '.')); ?></span>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">/ <?php echo e($kos->price_duration); ?></small>
                    </div>
                </div>
                
                <p class="text-muted small mb-3">
                    <i class="ti ti-map-pin me-1"></i> <?php echo e($kos->city); ?>

                </p>

                <hr class="border-light my-3">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-light text-dark border">
                        <i class="ti ti-door me-1"></i> <?php echo e($kos->kamar_count); ?> Kamar
                    </span>
                    <span class="badge <?php echo e($kos->kamar_tersedia_count > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?>">
                        <?php echo e($kos->kamar_tersedia_count); ?> Tersedia
                    </span>
                </div>
                
                <a href="<?php echo e(url('/owner/kos/'.$kos->id.'/foto')); ?>" class="btn btn-outline-primary w-100 mt-3 rounded-pill">
                    <i class="ti ti-photo me-1"></i> Kelola Foto
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="opacity-50 mb-3">
            <h5 class="text-muted">Tidak ada properti yang ditemukan.</h5>
            <a href="<?php echo e(url('/owner/kos/create')); ?>" class="btn btn-primary mt-2 rounded-pill">Tambah Sekarang</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center mt-5">
    <?php echo e($semua_kos->links()); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/kos/index.blade.php ENDPATH**/ ?>