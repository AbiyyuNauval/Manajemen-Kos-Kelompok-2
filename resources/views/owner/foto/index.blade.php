@extends('layouts.owner')

@section('content')

<!-- Judul Halaman dan Tombol Aksi -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Galeri Foto Kos</h3>
        <!-- Judul Dinamis -->
        <p class="card-subtitle">{{ $kos->name }}</p> 
    </div>
    <a href="{{ url('/owner/kos-saya') }}" class="btn btn-secondary">
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                     <div class="alert alert-success">
                        {{ session('success') }}
                     </div>
                @endif
                <!-- ==== AKHIR BLOK PESAN ==== -->

                <!-- Form Upload - Diperbaiki -->
                <form action="{{ url('/owner/kos/' . $kos->id . '/foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
 
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
                    @forelse ($fotos as $foto)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset($foto->url) }}" class="card-img-top" alt="{{ $foto->caption ?? 'Foto Kos' }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-2">
                                <p class="card-text small mb-1">{{ $foto->caption ?? 'Tanpa Caption' }}</p>

                                <!-- Tombol "Jadikan Utama" (SUDAH DIPERBAIKI) -->
                                @if ($foto->is_primary)
                                    <button class="btn btn-sm btn-success w-100 mb-1" disabled>Foto Utama</button>
                                @else
                                    <form action="{{ url('/owner/foto/' . $foto->id . '/set-primary') }}" method="POST" style="display: block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-success w-100 mb-1">Jadikan Utama</button>
                                    </form>
                                @endif

                                <!-- Tombol Hapus (SUDAH DIPERBAIKI) -->
                                <form action="{{ url('/owner/foto/' . $foto->id) }}" method="POST" style="display: block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Yakin ingin menghapus foto ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Ini akan tampil jika tidak ada foto sama sekali di database -->
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada foto yang di-upload untuk kos ini.</p>
                    </div>
                    @endforelse
                    <!-- AKHIR FORELSE -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection