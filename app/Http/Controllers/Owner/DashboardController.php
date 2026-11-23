<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Import Model
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\Booking;
use App\Models\ComplaintTicket;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil ID Owner yang sedang login
        $ownerId = Auth::id();

        // 2. Ambil Daftar ID Kos milik Owner ini
        $kosIds = Kos::where('owner_id', $ownerId)->pluck('id');

        // === STATISTIK KARTU (Cards) ===
        
        // Total Kos
        $totalKos = $kosIds->count();

        // Total Kamar
        $totalKamar = Kamar::whereIn('kos_id', $kosIds)->count();
        
        // Kamar Tersedia
        $kamarTersedia = Kamar::whereIn('kos_id', $kosIds)
                            ->where('is_available', 1) 
                            ->count();
        
        // Tiket Komplain Baru
        $tiketBaru = ComplaintTicket::whereIn('kos_id', $kosIds)
                        ->where('status', 'open')
                        ->count();
        
        // === PENDAPATAN BULAN INI ===
        $totalPemasukkan = Transaction::whereHas('booking', function($query) use ($kosIds) {
            $query->whereIn('kos_id', $kosIds);
        })
        ->where('status', 'success')
        ->where('type', 'sewa')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('amount');
        
        // === DATA GRAFIK 6 BULAN TERAKHIR (AREA CHART) ===
        $grafikData = Transaction::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(created_at, '%M') as bulan"),
            DB::raw('MONTH(created_at) as bulan_angka')
        )
        ->whereHas('booking', function($query) use ($kosIds) {
            $query->whereIn('kos_id', $kosIds);
        })
        ->where('status', 'success')
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('bulan', 'bulan_angka')
        ->orderBy('bulan_angka', 'asc')
        ->get();

        $grafikLabels = $grafikData->pluck('bulan');
        $grafikSeries = $grafikData->pluck('total');

        // === SUMBER PENDAPATAN PER KOS (DONUT CHART) ===
        $pendapatanPerKos = Transaction::select('kos.name', DB::raw('SUM(transactions.amount) as total'))
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('kos', 'bookings.kos_id', '=', 'kos.id')
            ->whereIn('kos.id', $kosIds) 
            ->where('transactions.status', 'success')
            ->where('transactions.type', 'sewa')
            ->groupBy('kos.name')
            ->get();

        $labelKos = $pendapatanPerKos->pluck('name');
        $totalPerKos = $pendapatanPerKos->pluck('total');

        // === TABEL RINGKASAN (Aktivitas Terbaru) ===
        $newBookings = Booking::with(['user', 'kos', 'kamar'])
                        ->whereIn('kos_id', $kosIds)
                        ->where('status', 'pending')
                        ->latest()
                        ->take(5)
                        ->get();

        $openTickets = ComplaintTicket::with(['user', 'kos'])
                        ->whereIn('kos_id', $kosIds)
                        ->where('status', 'open')
                        ->latest()
                        ->take(5)
                        ->get();

        // === KIRIM KE VIEW ===
        return view('owner.dashboard', compact(
            'totalKos',
            'totalKamar',
            'kamarTersedia',
            'tiketBaru',
            'totalPemasukkan',
            'grafikLabels',
            'grafikSeries',
            'newBookings',
            'openTickets',  // <-- Tadi kurang koma di sini
            'labelKos', 
            'totalPerKos'
        ));
    }
}   