  <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Utama</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/dashboard')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Manajemen Kos</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/kos-saya')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Kos Saya</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/kamar')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-bed"></i>
                </span>
                <span class="hide-menu">Manajemen Kamar</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Layanan</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/tiket')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-ticket"></i>
                </span>
                <span class="hide-menu">Tiket Komplain</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/booking')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu">Pengajuan Booking</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Akun</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(url('/owner/profil')); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-user-circle"></i>
                </span>
                <span class="hide-menu">Profil Akun</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo e(route('logout')); ?>" aria-expanded="false"> 
                <span>
                  <i class="ti ti-logout"></i>
                </span>
                <span class="hide-menu">Logout</span>
              </a>
            </li>
          </ul>
        </nav><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/partials/_owner-sidebar.blade.php ENDPATH**/ ?>