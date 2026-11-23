@extends('layouts.owner')

@section('content')

<style>
    /* Custom CSS untuk Tampilan Mewah */
    .card-hover {
        transition: all 0.3s ease;
        border: none;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .bg-gradient-primary-soft {
        background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
        opacity: 0.2;
    }
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    .text-gradient {
        background: -webkit-linear-gradient(45deg, #6a11cb, #2575fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Dashboard Overview</h3>
        <p class="text-muted mb-0">Ringkasan performa bisnis kos Anda hari ini.</p>
    </div>
    <div class="d-none d-sm-inline-block">
        <button class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="ti ti-download me-2"></i> Unduh Laporan
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover shadow-sm h-100 rounded-4 overflow-hidden position-relative">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-muted fw-semibold ls-1 mb-0">Total Properti</h6>
                    <div class="icon-box bg-light-primary text-primary">
                        <i class="ti ti-building-skyscraper"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1">{{ $totalKos }}</h2>
                <span class="text-success small fw-semibold"><i class="ti ti-trending-up"></i> Unit Aktif</span>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-10 p-3">
                <i class="ti ti-building-skyscraper fs-9 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-hover shadow-sm h-100 rounded-4 position-relative">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-muted fw-semibold ls-1 mb-0">Okupansi Kamar</h6>
                    <div class="icon-box bg-light-warning text-warning">
                        <i class="ti ti-bed"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end">
                    <h2 class="fw-bold text-dark mb-1">{{ $kamarTersedia }}</h2>
                    <span class="text-muted ms-2 mb-2">/ {{ $totalKamar }} Kosong</span>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    @php 
                        $persen = $totalKamar > 0 ? (($totalKamar - $kamarTersedia) / $totalKamar) * 100 : 0; 
                    @endphp
                    <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $persen }}%"></div>
                </div>
                <small class="text-muted mt-2 d-block">{{ round($persen) }}% Terisi</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-hover shadow-sm h-100 rounded-4 border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-muted fw-semibold ls-1 mb-0">Pemasukkan (Bulan Ini)</h6>
                    <div class="icon-box bg-light-success text-success">
                        <i class="ti ti-wallet"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPemasukkan, 0, ',', '.') }}</h2>
                <span class="text-muted small">Update Realtime</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-hover shadow-sm h-100 rounded-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-muted fw-semibold ls-1 mb-0">Booking Pending</h6>
                    <div class="icon-box bg-light-danger text-danger">
                        <i class="ti ti-bell-ringing"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1">{{ $newBookings->count() }}</h2>
                <span class="text-danger small fw-semibold">Butuh Konfirmasi!</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center rounded-top-4">
                <h5 class="fw-bold mb-0">Analisa Pendapatan</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button">6 Bulan Terakhir</button>
                </div>
            </div>
            <div class="card-body">
                <div id="grafikPemasukkanArea"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 rounded-top-4">
                <h5 class="fw-bold mb-0">Sumber Pendapatan</h5>
                <small class="text-muted">Kontribusi setiap kos terhadap total pendapatan</small>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div id="grafikSumberPendapatan" class="w-100 d-flex justify-content-center"></div>
                
                @if(count($labelKos) == 0)
                    <div class="text-center text-muted mt-3">
                        <i class="ti ti-chart-pie-off fs-8"></i>
                        <p>Belum ada data transaksi sukses.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center rounded-top-4">
                <h5 class="fw-bold mb-0">Booking & Komplain Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Pelanggan / Tipe</th>
                                <th class="border-0">Properti</th>
                                <th class="border-0">Tanggal / Status</th>
                                <th class="border-0 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newBookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary text-primary rounded-circle me-3 p-2">
                                            <i class="ti ti-user-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-0">{{ $booking->user->name ?? 'User' }}</h6>
                                            <span class="badge bg-primary rounded-pill" style="font-size: 0.7rem;">BOOKING BARU</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $booking->kos->name }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</span>
                                        <small class="text-muted">Menunggu Konfirmasi</small>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ url('/owner/booking') }}" class="btn btn-sm btn-primary rounded-pill px-3">Review</a>
                                </td>
                            </tr>
                            @endforeach

                            @foreach($openTickets as $ticket)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-danger text-danger rounded-circle me-3 p-2">
                                            <i class="ti ti-alert-triangle"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-0">{{ Str::limit($ticket->title, 20) }}</h6>
                                            <span class="badge bg-danger rounded-pill" style="font-size: 0.7rem;">KOMPLAIN</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ticket->kos->name }}</td>
                                <td>
                                    <span class="badge bg-light-danger text-danger">Open</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ url('/owner/tiket/'.$ticket->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Balas</a>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($newBookings->isEmpty() && $openTickets->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="64" class="opacity-50 mb-3">
                                    <h6 class="text-muted">Tidak ada aktivitas baru yang perlu perhatian Anda.</h6>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. CONFIG GRAFIK PENDAPATAN (AREA CHART - LEBIH MEWAH) ---
        var incomeLabels = {!! json_encode($grafikLabels) !!};
        var incomeData = {!! json_encode($grafikSeries) !!};

        var optionsArea = {
            series: [{
                name: 'Pemasukkan',
                data: incomeData
            }],
            chart: {
                type: 'area', // Ganti jadi Area biar ada gradasi bawahnya
                height: 350,
                toolbar: { show: false },
                fontFamily: "'Plus Jakarta Sans', sans-serif",
            },
            colors: ['#6a11cb'], // Warna ungu gradient start
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth', // Garis melengkung halus
                width: 3
            },
            xaxis: {
                categories: incomeLabels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        var chartArea = new ApexCharts(document.querySelector("#grafikPemasukkanArea"), optionsArea);
        chartArea.render();


        // --- 2. CONFIG GRAFIK SUMBER PENDAPATAN (DONUT CHART) ---
        // Data Kos dari Controller
        var kosLabels = {!! json_encode($labelKos) !!}; 
        var kosTotal = {!! json_encode($totalPerKos) !!}; 
        
        // Konversi string angka ke float (jika perlu)
        kosTotal = kosTotal.map(Number);

        var optionsDonut = {
            series: kosTotal,
            labels: kosLabels,
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: "'Plus Jakarta Sans', sans-serif",
            },
            colors: ['#2575fc', '#6a11cb', '#ff9f43', '#ef5350', '#00cfe8'], // Palet warna cerah modern
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { fontSize: '14px', color: '#999' },
                            value: { 
                                fontSize: '20px', 
                                fontWeight: 'bold', 
                                formatter: function (val) {
                                    return "Rp " + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(val);
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    // Hitung total semua
                                    let total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return "Rp " + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(total);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: false },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                itemMargin: { horizontal: 5, vertical: 5 }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        // Render hanya jika ada data
        if(kosTotal.length > 0 && kosTotal.some(x => x > 0)){
            var chartDonut = new ApexCharts(document.querySelector("#grafikSumberPendapatan"), optionsDonut);
            chartDonut.render();
        }
    });
</script>
@endpush