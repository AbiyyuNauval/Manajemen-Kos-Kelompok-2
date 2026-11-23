<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintTicket;
use App\Models\TicketResponse;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket (Search, Filter, Pagination)
     */
    public function index(Request $request)
    {
        $ownerId = Auth::id();
        
        // Data untuk Dropdown Filter
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();
        $kosIds = $daftar_kos->pluck('id');

        // Query Utama
        $tickets = ComplaintTicket::with(['user', 'kos'])
                    // Security: Hanya ambil tiket dari Kos milik Owner ini
                    ->whereIn('kos_id', $kosIds)
                    // Fitur Filter & Search dari Model (ScopeFilter)
                    ->filter($request->only(['search', 'kos_id', 'status', 'priority']))
                    // Urutkan: Open -> In Progress -> Resolved -> Closed
                    ->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')")
                    // Urutkan: High -> Medium -> Low
                    ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                    ->latest()
                    ->paginate(10) 
                    ->withQueryString();

        return view('owner.tiket.index', compact('tickets', 'daftar_kos'));
    }

    /**
     * Menampilkan Detail Tiket (Halaman Chat)
     */
    public function show(string $id)
    {
        // Pastikan tiket milik owner yang login (via kos_id)
        $ownerId = Auth::id();
        $kosIds = Kos::where('owner_id', $ownerId)->pluck('id');

        $ticket = ComplaintTicket::with(['user', 'kos', 'responses.user'])
                    ->whereIn('kos_id', $kosIds)
                    ->findOrFail($id);

        return view('owner.tiket.show', compact('ticket'));
    }

    /**
     * Menyimpan balasan chat (Reply)
     */
    public function store(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        TicketResponse::create([
            'ticket_id' => $id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Auto update status jika masih open
        $ticket = ComplaintTicket::findOrFail($id);
        if ($ticket->status == 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Balasan terkirim.');
    }
    
    /**
     * Update Status Tiket (Quick Action)
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
        
        $ticket = ComplaintTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tiket diperbarui.');
    }

    /**
     * Hapus Tiket
     */
    public function destroy(string $id)
    {
        $ticket = ComplaintTicket::findOrFail($id);
        $ticket->delete();

        return redirect('/owner/tiket')->with('success', 'Tiket berhasil dihapus.');
    }
    
    // Fungsi Resource lain (tidak dipakai)
    public function create() {}
    public function edit(string $id) {}
}