

<?php $__env->startSection('content'); ?>

<h3 class="mb-3">Edit Data Kos: <?php echo e($kos->name); ?></h3>

<ul class="nav nav-tabs" id="editKosTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="info-dasar-tab" data-bs-toggle="tab" data-bs-target="#info-dasar" type="button" role="tab" aria-controls="info-dasar" aria-selected="true">
      <i class="ti ti-file-text me-1"></i>Info Dasar
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="fasilitas-tab" data-bs-toggle="tab" data-bs-target="#fasilitas" type="button" role="tab" aria-controls="fasilitas" aria-selected="false">
      <i class="ti ti-star me-1"></i>Fasilitas & Peraturan
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="lokasi-tab" data-bs-toggle="tab" data-bs-target="#lokasi" type="button" role="tab" aria-controls="lokasi" aria-selected="false">
      <i class="ti ti-map-pin me-1"></i>Lokasi / Maps
    </button>
  </li>
</ul>
<form action="<?php echo e(url('/owner/kos/' . $kos->id)); ?>" method="POST">
  <?php echo csrf_field(); ?>
  <?php echo method_field('PUT'); ?>

  <div class="card shadow-sm">
    <div class="card-body">
      
      <div class="tab-content" id="editKosTabsContent">
        
        <div class="tab-pane fade show active" id="info-dasar" role="tabpanel" aria-labelledby="info-dasar-tab">
          <h5 class="card-title fw-semibold mb-4">Informasi Dasar Properti</h5>
          
          <div class="row">
              <div class="col-md-8 mb-3">
                  <label for="nama_kos" class="form-label">Nama Kos</label>
                  <input type="text" class="form-control" id="nama_kos" name="nama_kos" placeholder="Contoh: Kos Putra Idaman" required value="<?php echo e($kos->name); ?>">
              </div>
              <div class="col-md-4 mb-3">
                  <label for="tipe_kos" class="form-label">Tipe Kos</label>
                  <select class="form-select" id="tipe_kos" name="tipe_kos" required>
                      <option value="pria" <?php echo e($kos->kos_type == 'pria' ? 'selected' : ''); ?>>Pria</option>
                      <option value="wanita" <?php echo e($kos->kos_type == 'wanita' ? 'selected' : ''); ?>>Wanita</option>
                      <option value="campur" <?php echo e($kos->kos_type == 'campur' ? 'selected' : ''); ?>>Campur</option>
                  </select>
              </div>
          </div>
          <div class="mb-3">
              <label for="alamat" class="form-label">Alamat Lengkap</label>
              <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Contoh: Jl. Merdeka No. 10..." required><?php echo e($kos->address); ?></textarea>
          </div>
          <div class="row">
              <div class="col-md-6 mb-3">
                  <label for="kota" class="form-label">Kota</label>
                  <input type="text" class="form-control" id="kota" name="kota" placeholder="Contoh: Jakarta Selatan" required value="<?php echo e($kos->city); ?>">
              </div>
              <div class="col-md-6 mb-3">
                  <label for="ukuran" class="form-label">Ukuran Kamar (Opsional)</label>
                  <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Contoh: 3x4 Meter" value="<?php echo e($kos->size); ?>">
              </div>
          </div>
          <div class="row">
              <div class="col-md-8 mb-3">
                  <label for="harga" class="form-label">Harga Sewa</label>
                  <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 2000000" required value="<?php echo e($kos->price); ?>">
                  </div>
              </div>
              <div class="col-md-4 mb-3">
                  <label for="durasi_harga" class="form-label">Durasi Harga</label>
                  <select class="form-select" id="durasi_harga" name="durasi_harga" required>
                      <option value="month" <?php echo e($kos->price_duration == 'month' ? 'selected' : ''); ?>>Per Bulan</option>
                      <option value="year" <?php echo e($kos->price_duration == 'year' ? 'selected' : ''); ?>>Per Tahun</option>
                  </select>
              </div>
          </div>
          <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi Kos (Opsional)</label>
              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan tentang kos Anda..."><?php echo e($kos->description); ?></textarea>
          </div>
        </div>
        <div class="tab-pane fade" id="fasilitas" role="tabpanel" aria-labelledby="fasilitas-tab">
          <h5 class="card-title fw-semibold mb-4">Fasilitas & Peraturan</h5>
          <p class="card-subtitle mb-3">Isi dengan fasilitas yang Anda sediakan. Pisahkan setiap fasilitas dengan koma (Contoh: AC, TV, Kasur, Lemari).</p>
          
          <div class="mb-3">
              <label for="fasilitas_kamar" class="form-label">Fasilitas Kamar</label>
              <textarea class="form-control" id="fasilitas_kamar" name="fasilitas_kamar" rows="3" placeholder="Contoh: AC, Kasur, Meja Belajar, Lemari, WiFi, Kamar Mandi Dalam"><?php echo e($kos->fasilitas_kamar); ?></textarea>
          </div>
          <div class="mb-3">
              <label for="fasilitas_umum" class="form-label">Fasilitas Umum</label>
              <textarea class="form-control" id="fasilitas_umum" name="fasilitas_umum" rows="3" placeholder="Contoh: Dapur Bersama, Kulkas Bersama, Ruang Tamu, TV Bersama"><?php echo e($kos->fasilitas_umum); ?></textarea>
          </div>
          <div class="mb-3">
              <label for="fasilitas_parkir" class="form-label">Fasilitas Parkir</label>
              <textarea class="form-control" id="fasilitas_parkir" name="fasilitas_parkir" rows="2" placeholder="Contoh: Parkir Motor, Parkir Mobil"><?php echo e($kos->fasilitas_parkir); ?></textarea>
          </div>
          <div class="mb-3">
              <label for="peraturan_kos" class="form-label">Peraturan Kos</label>
              <textarea class="form-control" id="peraturan_kos" name="peraturan_kos" rows="4" placeholder="Contoh: Dilarang membawa hewan, Tamu menginap dikenakan biaya, Akses 24 Jam"><?php echo e($kos->peraturan_kos); ?></textarea>
          </div>
        </div>
        <div class="tab-pane fade" id="lokasi" role="tabpanel" aria-labelledby="lokasi-tab">
          <h5 class="card-title fw-semibold mb-4">Lokasi & Maps</h5>
          <p class="card-subtitle mb-3">Anda bisa mendapatkan Latitude & Longitude dari Google Maps. Klik kanan pada lokasi di Gmaps, lalu klik angka koordinatnya untuk menyalin.</p>
          
          <div class="row">
              <div class="col-md-6 mb-3">
                  <label for="latitude" class="form-label">Latitude (Opsional)</label>
                  <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Contoh: -6.2088" value="<?php echo e($kos->latitude); ?>">
              </div>
              <div class="col-md-6 mb-3">
                  <label for="longitude" class="form-label">Longitude (Opsional)</label>
                  <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Contoh: 106.8456" value="<?php echo e($kos->longitude); ?>">
              </div>
          </div>
          <p class="text-muted small">Contoh: Jika Anda meng-copy `-6.2088, 106.8456` dari Gmaps. Masukkan `-6.2088` ke Latitude dan `106.8456` ke Longitude.</p>
        </div>
        </div>
    </div>
  </div>

  <div class="d-flex justify-content-end gap-2 mt-3">
      <a href="<?php echo e(url('/owner/kos-saya')); ?>" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan Perubahan Kos</button>
  </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\manajemen-kos\resources\views/owner/kos/edit.blade.php ENDPATH**/ ?>