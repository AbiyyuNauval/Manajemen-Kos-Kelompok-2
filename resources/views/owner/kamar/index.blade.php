@extends('layouts.owner')

@section('content')

<style>
    /* Styling Khusus Tier SSSS */
    .filter-card {
        border: none;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-control-search {
        border: 1px solid #eef1f6;
        background-color: #f8f9fa;
        padding-left: 2.5rem;
        border-radius: 10px;
    }
    .form-control-search:focus {
        background-color: #fff;
        border-color: #6a11cb;
        box-shadow: 0 0 0 4px rgba(106, 17, 203, 0.1);
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a1aab5;
    }
    .table-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #5d6d7e;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }
    .table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Manajemen Kamar</h3>
        <p class="text-muted mb-0">Kelola ketersediaan dan data kamar kos Anda.</p>
    </div>
    <div>
        <a href="{{ url('/owner/kamar/create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="ti ti-plus me-2"></i>Tambah Kamar
        </a>
    </div>
</div>

@if (session('success'))
   <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
      <div class="d-flex align-items-center">
          <i class="ti ti-circle-check fs-5 me-2"></i>
          <strong>Berhasil!</strong> {{ session('success') }}
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
@endif

<div class="card filter-card mb-4">
    <div class="card-body p-4">
        <form action="{{ url('/owner/kamar') }}" method="GET">
            <div class="row g-3">
                
                <div class="col-lg-4 col-md-6">
                    <div class="position-relative">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control form-control-search py-2" 
                               placeholder="Cari nomor kamar..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-3 col-md-3">
                    <select name="kos_id" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Kos Saya</option>
                        @foreach($daftar_kos as $kos)
                            <option value="{{ $kos->id }}" {{ request('kos_id') == $kos->id ? 'selected' : '' }}>
                                {{ $kos->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3">
                    <select name="status" class="form-select py-2 border-light bg-light" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>✅ Tersedia</option>
                        <option value="penuh" {{ request('status') == 'penuh' ? 'selected' : '' }}>❌ Penuh</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 py-2">Cari</button>
                    @if(request()->hasAny(['search', 'kos_id', 'status']))
                        <a href="{{ url('/owner/kamar') }}" class="btn btn-light py-2" data-bs-toggle="tooltip" title="Reset Filter">
                            <i class="ti ti-refresh"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

<div class="card table-card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No.</th>
                        <th>Nomor Kamar</th>
                        <th>Properti (Kos)</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($semua_kamar as $index => $kamar)
                    <tr>
                        <td class="ps-4 text-muted">
                            {{ ($semua_kamar->currentPage() - 1) * $semua_kamar->perPage() + $loop->iteration }}
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-primary text-primary rounded px-2 py-1 me-3 fw-bold">
                                    {{ substr($kamar->room_number, 0, 3) }}
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-0 text-dark">{{ $kamar->room_number }}</h6>
                                    <small class="text-muted">ID: #{{ $kamar->id }}</small>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="d-block fw-semibold text-dark">{{ $kamar->kos->name ?? 'Deleted' }}</span>
                            <span class="d-block text-muted small">
                                <i class="ti ti-map-pin fs-2"></i> {{ $kamar->kos->city ?? '-' }}
                            </span>
                        </td>
                        
                        <td>
                            @if($kamar->is_available)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                    <i class="ti ti-circle-check me-1"></i> Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                    <i class="ti ti-lock me-1"></i> Terisi
                                </span>
                            @endif
                        </td>
                        
                        <td class="text-end pe-4">
                            <a href="{{ url('/owner/kamar/' . $kamar->id . '/edit') }}" 
                               class="action-btn bg-light-warning text-warning me-2" 
                               data-bs-toggle="tooltip" 
                               title="Edit Kamar">
                                <i class="ti ti-edit"></i>
                            </a>
                            
                            <form action="{{ url('/owner/kamar/' . $kamar->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="action-btn bg-light-danger text-danger border-0" 
                                        onclick="return confirm('Yakin ingin menghapus kamar ini? Data booking terkait mungkin akan error.')"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Kamar">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="opacity-50 mb-3">
                                <h5 class="text-muted fw-bold">Tidak ada data kamar ditemukan</h5>
                                <p class="text-muted small">Coba ubah kata kunci pencarian atau tambah kamar baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-end">
            {{ $semua_kamar->onEachSide(1)->links() }}
        </div>
    </div>
</div>

@endsection