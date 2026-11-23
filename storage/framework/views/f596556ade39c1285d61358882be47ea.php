

<?php $__env->startSection('content'); ?>

<style>
    /* Styling Tier SSSS */
    .filter-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .form-control-search { border: 1px solid #eef1f6; background-color: #f8f9fa; padding-left: 2.5rem; border-radius: 10px; }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a1aab5; }
    
    /* Custom Badges */
    .badge-soft-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .badge-soft-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .badge-soft-success { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
    .badge-soft-info { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .badge-soft-dark { background-color: rgba(33, 37, 41, 0.1); color: #212529; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Pusat Bantuan & Komplain</h3>
        <p class="text-muted mb-0">Kelola keluhan penyewa dan pantau penyelesaian masalah.</p>
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
        <form action="<?php echo e(url('/owner/tiket')); ?>" method="GET">
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="position-relative">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-search py-2" 
                               placeholder="Cari judul, user, atau isi..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <select name="kos_id" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Properti</option>
                        <?php $__currentLoopData = $daftar_kos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kos->id); ?>" <?php echo e(request('kos_id') == $kos->id ? 'selected' : ''); ?>>
                                <?php echo e($kos->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-2">
                    <select name="status" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="open" <?php echo e(request('status') == 'open' ? 'selected' : ''); ?>>🔴 Open</option>
                        <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>🟡 In Progress</option>
                        <option value="resolved" <?php echo e(request('status') == 'resolved' ? 'selected' : ''); ?>>🟢 Resolved</option>
                        <option value="closed" <?php echo e(request('status') == 'closed' ? 'selected' : ''); ?>>⚫ Closed</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <select name="priority" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Prioritas</option>
                        <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>🔥 High</option>
                        <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>⚡ Medium</option>
                        <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>💤 Low</option>
                    </select>
                </div>

                <?php if(request()->hasAny(['search', 'kos_id', 'status', 'priority'])): ?>
                <div class="col-lg-1">
                    <a href="<?php echo e(url('/owner/tiket')); ?>" class="btn btn-light w-100 py-2" data-bs-toggle="tooltip" title="Reset Filter">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Pelapor</th>
                        <th>Masalah / Judul</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tiket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-primary text-primary rounded-circle me-3 p-2">
                                    <span class="fw-bold"><?php echo e(substr($tiket->user->name ?? 'U', 0, 1)); ?></span>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-0 text-dark"><?php echo e($tiket->user->name ?? 'User Hapus'); ?></h6>
                                    <small class="text-muted"><?php echo e($tiket->kos->name ?? '-'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column" style="max-width: 300px;">
                                <span class="fw-semibold text-dark text-truncate"><?php echo e($tiket->title); ?></span>
                                <small class="text-muted text-truncate"><?php echo e($tiket->description); ?></small>
                                <small class="text-muted mt-1" style="font-size: 0.75rem;">
                                    <i class="ti ti-clock me-1"></i> <?php echo e($tiket->created_at->diffForHumans()); ?>

                                </small>
                            </div>
                        </td>
                        <td>
                            <?php if($tiket->priority == 'high'): ?>
                                <span class="badge bg-danger rounded-pill px-3"><i class="ti ti-flame me-1"></i> High</span>
                            <?php elseif($tiket->priority == 'medium'): ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3">Medium</span>
                            <?php else: ?>
                                <span class="badge bg-info rounded-pill px-3">Low</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($tiket->status == 'open'): ?>
                                <span class="badge badge-soft-danger px-3 py-2 rounded-pill fw-bold">OPEN</span>
                            <?php elseif($tiket->status == 'in_progress'): ?>
                                <span class="badge badge-soft-warning px-3 py-2 rounded-pill fw-bold">PROSES</span>
                            <?php elseif($tiket->status == 'resolved'): ?>
                                <span class="badge badge-soft-success px-3 py-2 rounded-pill fw-bold">SELESAI</span>
                            <?php else: ?>
                                <span class="badge badge-soft-dark px-3 py-2 rounded-pill fw-bold">TUTUP</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                          <a href="<?php echo e(url('/owner/tiket/' . $tiket->id)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4">
    <i class="ti ti-message-circle me-1"></i> Lihat & Balas
</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="60" class="opacity-50 mb-3">
                            <p class="text-muted mb-0">Hore! Tidak ada komplain yang sesuai filter.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-end">
            <?php echo e($tickets->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/tiket/index.blade.php ENDPATH**/ ?>