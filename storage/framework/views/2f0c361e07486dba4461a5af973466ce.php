<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Owner - Kosvia</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('assets')); ?>/images/logos/favicon.png" />
 <link rel="stylesheet" href="<?php echo e(asset('assets')); ?>/css/styles.min.css" />
<link rel="stylesheet" href="<?php echo e(asset('assets/css/kosvia-custom.css?v=1.1')); ?>" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <div class="app-topstrip bg-dark py-2 px-3 w-100 d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center justify-content-center gap-5">
        <a class="d-flex justify-content-center" href="<?php echo e(url('/owner/dashboard')); ?>">
          <img src="https://placehold.co/120x35/FFFFFF/343A40?text=Kosvia&font=sans-serif" alt="Kosvia Logo" >
        </a>
      </div>
      <div class="d-lg-flex align-items-center gap-2">
        <p class="text-white mb-0 fs-5">Kosvia Owner Dashboard</p>
      </div>
    </div>
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?php echo e(url('/owner/dashboard')); ?>" class="text-nowrap logo-img">
            <img src="https://placehold.co/150x40/2A3547/FFFFFF?text=Kosvia&font=sans-serif" alt="Kosvia Logo" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>

        <?php echo $__env->make('partials._owner-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>
      </aside>
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link " href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-bell"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="message-body">
                  <a href="javascript:void(0)" class="dropdown-item">
                    Notifikasi 1
                  </a>
                  <a href="javascript:void(0)" class="dropdown-item">
                    Notifikasi 2
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              
              <li class="nav-item dropdown">
                <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?php echo e(asset('assets')); ?>/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="<?php echo e(url('/owner/profil')); ?>" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">Profil Saya</p>
                    </a>
                    <a href="#" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          
          <?php echo $__env->yieldContent('content'); ?>

                  </div>
                </div>

              </div>

            </div>
          </div>
          
          </div>
      </div>
    </div>
  </div>
  <script src="<?php echo e(asset('assets')); ?>/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/js/sidebarmenu.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/js/app.min.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/libs/simplebar/dist/simplebar.js"></script>
  <script src="<?php echo e(asset('assets')); ?>/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/layouts/owner.blade.php ENDPATH**/ ?>