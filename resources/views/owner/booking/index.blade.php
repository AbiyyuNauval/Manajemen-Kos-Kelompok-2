@extends('layouts.owner')

@section('content')

<style>
    /* Reuse style Tier SSSS */
    .filter-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .form-control-search { border: 1px solid #eef1f6; background-color: #f8f9fa; padding-left: 2.5rem; border-radius: 10px; }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a1aab5; }
    .nav-tabs .nav-link { border: none; color: #5d6d7e; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 2px solid transparent; }
    .nav-tabs .nav-link.active { color: #6a11cb; border-bottom: 2px solid #6a11cb; background: transparent; }
    .nav-tabs .nav-link:hover { color: #6a11cb; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Manajemen Booking</h3>
        <p class="text-muted mb-0">Kelola pengajuan sewa dan riwayat penghuni.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="ti ti-circle-check fs-5 me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card filter-card mb-4">
    <div class="card-body p-4">
        <form action="{{ url('/owner/booking') }}" method="GET">
            <div class="row g-3">
                <div class="col-lg-5">
                    <div class="position-relative">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-search py-2" 
                               placeholder="Cari nama penyewa..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <select name="kos_id" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Properti</option>
                        @foreach($daftar_kos as $kos)
                            <option value="{{ $kos->id }}" {{ request('kos_id') == $kos->id ? 'selected' : '' }}>
                                {{ $kos->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary w-100 py-2">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'kos_id']))
                <div class="col-lg-1">
                    <a href="{{ url('/owner/booking') }}" class="btn btn-light w-100 py-2" data-bs-toggle="tooltip" title="Reset">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        
        <ul class="nav nav-tabs px-4 pt-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="baru-tab" data-bs-toggle="tab" data-bs-target="#baru" type="button" role="tab">
                    Pengajuan Baru 
                    @if($pendingBookings->total() > 0)
                        <span class="badge bg-danger rounded-pill ms-2">{{ $pendingBookings->total() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">
                    Riwayat Booking
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="baru" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Pelanggan</th>
                                <th>Kos / Kamar</th>
                                <th>Rencana Masuk</th>
                                <th>Pembayaran</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingBookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary text-primary rounded-circle me-3 p-2">
                                            <span class="fw-bold">{{ substr($booking->user->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-0 text-dark">{{ $booking->user->name ?? 'User Hapus' }}</h6>
                                            <small class="text-muted">{{ $booking->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold text-dark">{{ $booking->kos->name }}</span>
                                    <span class="badge bg-light border text-dark">Kamar {{ $booking->kamar->room_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->start_date)->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning-subtle text-warning border border-warning">{{ strtoupper($booking->payment_type) }}</span></td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ url('/owner/booking/'.$booking->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="terima">
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3"><i class="ti ti-check me-1"></i> Terima</button>
                                        </form>
                                        <form action="{{ url('/owner/booking/'.$booking->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="tolak">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="60" class="opacity-50 mb-3">
                                    <p class="text-muted mb-0">Tidak ada pengajuan booking baru.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top">
                    {{ $pendingBookings->appends(['history_page' => request('history_page')])->links() }}
                </div>
            </div>

            <div class="tab-pane fade" id="riwayat" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Pelanggan</th>
                                <th>Properti</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historyBookings as $booking)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    {{ $booking->kos->name }} 
                                    <small class="text-muted d-block">No. {{ $booking->kamar->room_number }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/y') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/y') }}
                                </td>
                                <td>
                                    @if($booking->status == 'active') 
                                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill">Aktif</span>
                                    @elseif($booking->status == 'rejected') 
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Ditolak</span>
                                    @elseif($booking->status == 'completed') 
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Selesai</span>
                                    @else 
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $booking->status }}</span> 
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($booking->status == 'active')
                                        <form action="{{ url('/owner/booking/'.$booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Selesaikan masa sewa?')">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="selesai">
                                            <button class="btn btn-sm btn-light text-primary" title="Tandai Selesai"><i class="ti ti-circle-check"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ url('/owner/booking/'.$booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat booking.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top">
                    {{ $historyBookings->appends(['pending_page' => request('pending_page')])->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection