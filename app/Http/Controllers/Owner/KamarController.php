<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class KamarController extends Controller
{
    /**
     * Menampilkan daftar kamar (Dengan Search, Filter & Pagination)
     */
    public function index(Request $request)
    {
        $ownerId = Auth::id(); 
        
        // 1. Ambil daftar kos untuk Dropdown Filter di View
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();

        // 2. Query Utama
        $semua_kamar = Kamar::with('kos')
                        // A. Security: Hanya ambil kamar milik owner yang login
                        ->whereHas('kos', function($query) use ($ownerId) {
                            $query->where('owner_id', $ownerId); 
                        })
                        // B. Filter & Search (Memanggil scopeFilter di Model)
                        // Kita ambil parameter: search, kos_id, status
                        ->filter($request->only(['search', 'kos_id', 'status']))
                        ->latest()
                        // C. Pagination (10 data per halaman) & Pertahankan query string
                        ->paginate(10)
                        ->withQueryString();

        return view('owner.kamar.index', [
            'semua_kamar' => $semua_kamar,
            'daftar_kos' => $daftar_kos // Dikirim untuk dropdown filter
        ]);
    }

    /**
     * Menampilkan form tambah kamar baru
     */
    public function create()
    {
        $ownerId = Auth::id();
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();

        return view('owner.kamar.create', [
            'daftar_kos' => $daftar_kos
        ]);
    }

    /**
     * Menyimpan kamar baru
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kos_id' => 'required|exists:kos,id', 
            'room_number' => 'required|string|max:50',
            'is_available' => 'required|boolean',
        ]);
        
        Kamar::create($validatedData);

        return redirect('/owner/kamar')->with('success', 'Kamar baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kamar
     */
    public function edit(string $id)
    {
        $kamar = Kamar::findOrFail($id);
        $ownerId = Auth::id();
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();
        
        return view('owner.kamar.edit', [
            'kamar' => $kamar,
            'daftar_kos' => $daftar_kos
        ]);
    }

    /**
     * Update data kamar
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'kos_id' => 'required|exists:kos,id', 
            'room_number' => 'required|string|max:50',
            'is_available' => 'required|boolean',
        ]);

        $kamar = Kamar::findOrFail($id);
        $kamar->update($validatedData);

        return redirect('/owner/kamar')->with('success', 'Data kamar berhasil diperbarui!');
    }

    /**
     * Hapus kamar
     */
    public function destroy(string $id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect('/owner/kamar')->with('success', 'Kamar berhasil dihapus!');
    }
}