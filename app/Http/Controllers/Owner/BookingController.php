<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = Auth::id();
        
        // Data untuk Dropdown Filter
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();
        $kosIds = $daftar_kos->pluck('id');

        // 1. Ambil Booking PENDING (Pengajuan Baru)
        // Kita terapkan Filter Search di sini juga
        $pendingBookings = Booking::with(['user', 'kos', 'kamar'])
                            ->whereIn('kos_id', $kosIds)
                            ->where('status', 'pending')
                            ->filter($request->only(['search', 'kos_id'])) // Panggil ScopeFilter
                            ->latest()
                            ->paginate(5, ['*'], 'pending_page') // Pagination khusus tab pending
                            ->withQueryString();

        // 2. Ambil Riwayat Booking (Active, Completed, Rejected)
        $historyBookings = Booking::with(['user', 'kos', 'kamar'])
                            ->whereIn('kos_id', $kosIds)
                            ->where('status', '!=', 'pending')
                            ->filter($request->only(['search', 'kos_id', 'status'])) // Filter lengkap
                            ->latest()
                            ->paginate(10, ['*'], 'history_page') // Pagination khusus tab riwayat
                            ->withQueryString();

        return view('owner.booking.index', compact('pendingBookings', 'historyBookings', 'daftar_kos'));
    }

    // ... (Fungsi update dan destroy biarkan sama seperti sebelumnya) ...
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($request->action == 'terima') {
            $booking->status = 'active';
            $booking->kamar->update(['is_available' => 0]); 
            $msg = 'Booking diterima! Penghuni mulai ngekos.';
        } elseif ($request->action == 'tolak') {
            $booking->status = 'rejected';
            $msg = 'Booking ditolak.';
        } elseif ($request->action == 'selesai') {
            $booking->status = 'completed';
            $booking->kamar->update(['is_available' => 1]); 
            $msg = 'Sewa selesai.';
        } elseif ($request->action == 'cancel') { // Tambahan cancel
             $booking->status = 'cancelled';
             $booking->kamar->update(['is_available' => 1]);
             $msg = 'Booking dibatalkan.';
        }

        $booking->save();
        return redirect()->back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->back()->with('success', 'Data booking dihapus.');
    }
}