@extends('layouts.owner')

@section('content')

<!-- Judul Halaman -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Balas Tiket Komplain</h3>
        <p class="card-subtitle">Tiket ID: #{{ $ticket->id }}</p>
    </div>
    <a href="{{ url('/owner/tiket') }}" class="btn btn-secondary">
        &lt; Kembali ke Daftar Tiket
    </a>
</div>

<div class="row">
    <!-- Kolom Kiri: Detail Tiket & Form Balasan -->
    <div class="col-lg-8">
        
        <!-- Form Balasan (Kita letakkan di atas agar mudah diakses) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3">Kirim Balasan Anda</h5>
                <form action="{{ url('/owner/tiket/' . $ticket->id . '/reply') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan Balasan</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Ketik balasan Anda di sini..." required></textarea>
                    </div>
                    <!-- Nanti kita bisa tambahkan input 'attachment' di sini -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-send me-1"></i> Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Akhir Form Balasan -->

        <!-- Riwayat Percakapan (Chat) -->
        <h5 class="fw-semibold mb-3">Riwayat Percakapan</h5>

        <!-- Tiket Asli (Komplain Awal) -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light-subtle d-flex justify-content-between">
                <h6 class="fw-semibold mb-0 text-dark">
                    <i class="ti ti-user-circle me-1"></i>
                    {{ $ticket->user->name ?? 'Pelanggan' }} (Pengirim Asli)
                </h6>
                <span class="text-muted small">{{ $ticket->created_at ? \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') : 'N/A' }}</span>
            </div>
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $ticket->title }}</h5>
                <p class="card-text">
                    {!! nl2br(e($ticket->description)) !!} <!-- nl2br agar 'Enter' tetap tampil -->
                </p>
            </div>
        </div>

        <!-- Balasan-balasan (Looping) -->
        @forelse ($ticket->responses as $response)
        <div class="card shadow-sm mb-3 
            @if($response->user_id == 1) ms-5 bg-light-primary @else me-5 bg-light @endif"> 
            <!-- Ganti '1' dengan ID Owner jika sudah login. Ini untuk styling chat 'Saya' vs 'Dia' -->
            
            <div class="card-header d-flex justify-content-between">
                <h6 class="fw-semibold mb-0 
                    @if($response->user_id == 1) text-primary @else text-dark @endif">
                    <i class="ti ti-user-circle me-1"></i>
                    @if($response->user_id == 1)
                        Anda (Owner)
                    @else
                        {{ $response->user->name ?? 'Pelanggan' }}
                    @endif
                </h6>
                <span class="text-muted small">{{ $response->created_at ? \Carbon\Carbon::parse($response->created_at)->format('d M Y, H:i') : 'N/A' }}</span>
            </div>
            <div class="card-body">
                <p class="card-text">
                    {!! nl2br(e($response->message)) !!}
                </p>
                <!-- Nanti kita tampilkan attachment jika ada -->
            </div>
        </div>
        @empty
        <p class="text-center text-muted">Belum ada balasan untuk tiket ini.</p>
        @endforelse
        <!-- Akhir Riwayat Percakapan -->

    </div>

    <!-- Kolom Kanan: Info Detail Tiket -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Detail Tiket</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Status:</span>
                        <!-- Logika Badge Status -->
                        @if($ticket->status == 'open')
                            <span class="badge bg-danger-subtle text-danger fw-semibold">Open</span>
                        @elseif($ticket->status == 'in_progress')
                            <span class="badge bg-warning-subtle text-warning fw-semibold">In Progress</span>
                        @elseif($ticket->status == 'resolved')
                            <span class="badge bg-success-subtle text-success fw-semibold">Resolved</span>
                        @else
                            <span class="badge bg-dark-subtle text-dark fw-semibold">Closed</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Prioritas:</span>
                        <!-- Logika Badge Prioritas -->
                        @if($ticket->priority == 'high')
                            <span class="badge bg-danger">Tinggi</span>
                        @elseif($ticket->priority == 'medium')
                            <span class="badge bg-warning">Medium</span>
                        @else
                            <span class="badge bg-info">Rendah</span>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Pelanggan:</span>
                        <h6 class="fw-semibold mb-0">{{ $ticket->user->name ?? 'User Dihapus' }}</h6>
                        <span class="small">{{ $ticket->user->email ?? 'Email Tidak Ada' }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Properti Kos:</span>
                        <h6 class="fw-semibold mb-0">{{ $ticket->kos->name ?? 'N/A' }}</h6>
                    </li>
                </ul>

                <!-- Tombol Aksi Status -->
                <div class="d-grid gap-2 mt-4">
                    <!-- Form untuk "Tutup Tiket" -->
                    <form action="{{ url('/owner/tiket/' . $ticket->id . '/status') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="ti ti-lock me-1"></i> Tutup Tiket Ini
                        </button>
                    </form>
                    <!-- Form untuk "Buka Kembali" (jika sudah ditutup) -->
                     <form action="{{ url('/owner/tiket/' . $ticket->id . '/status') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="ti ti-lock-open me-1"></i> Buka Kembali Tiket
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection