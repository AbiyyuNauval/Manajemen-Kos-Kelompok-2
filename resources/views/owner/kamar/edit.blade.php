@extends('layouts.owner')

@section('content')

<!-- Judul Halaman -->
<h3 class="mb-3">Edit Data Kamar</h3>

<!-- Menampilkan pesan error jika ada -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Kartu Utama untuk Form -->
<div class="card">
    <div class="card-body">
        
        <h5 class="card-title fw-semibold mb-4">Formulir Edit Kamar</h5>
        <p class="card-subtitle mb-3">
            Perbarui data kamar Anda di bawah ini.
        </p>

        <!-- ==== FORM ACTION DIUBAH KE 'update' ==== -->
        <form action="{{ url('/owner/kamar/' . $kamar->id) }}" method="POST">
            @csrf 
            @method('PUT') <!-- <-- WAJIB UNTUK EDIT -->

            <!-- Baris 1: Pilih Kos (Sudah terisi data lama) -->
            <div class="mb-3">
                <label for="kos_id" class="form-label">Pilih Properti Kos</label>
                <select class="form-select" id="kos_id" name="kos_id" required>
                    <option value="" disabled>-- Pilih Kos --</option>
                    
                    @foreach ($daftar_kos as $kos)
                        <option value="{{ $kos->id }}" {{ $kamar->kos_id == $kos->id ? 'selected' : '' }}>
                            {{ $kos->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Baris 2: Nomor Kamar (Sudah terisi data lama) -->
            <div class="mb-3">
                <label for="room_number" class="form-label">Nomor Kamar</label>
                <input type="text" class="form-control" id="room_number" name="room_number" 
                       placeholder="Contoh: A103" required 
                       value="{{ $kamar->room_number }}"> <!-- <-- DATA LAMA DITAMPILKAN -->
            </div>

            <!-- Baris 3: Status Ketersediaan (Sudah terisi data lama) -->
            <div class="mb-3">
                <label for="is_available" class="form-label">Status Ketersediaan</label>
                <select class="form-select" id="is_available" name="is_available" required>
                    <option value="1" {{ $kamar->is_available == 1 ? 'selected' : '' }}>Tersedia (Kosong)</option>
                    <option value="0" {{ $kamar->is_available == 0 ? 'selected' : '' }}>Tidak Tersedia (Sudah Terisi)</option>
                </select>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ url('/owner/kamar') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Kamar</button> <!-- Tombol diubah -->
            </div>

        </form>

    </div>
</div>

@endsection