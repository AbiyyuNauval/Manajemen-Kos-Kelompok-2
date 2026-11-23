<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class KosController extends Controller
{
    /**
     * Menampilkan daftar Kos (Dengan Search & Pagination)
     */
    public function index(Request $request)
    {
        $ownerId = Auth::id(); 
        
        $data_kos = Kos::with('primaryPhoto')
                        ->withCount([
                            'kamar', 
                            'kamar as kamar_tersedia_count' => function ($query) {
                                $query->where('is_available', 1);
                            }
                        ])
                        ->where('owner_id', $ownerId)
                        // IMPLEMENTASI MODUL 12: Search
                        ->filter($request->only(['search'])) 
                        ->latest()
                        // IMPLEMENTASI MODUL 12: Pagination (6 per halaman untuk Grid)
                        ->paginate(6) 
                        ->withQueryString();
        
        return view('owner.kos.index', [
            'semua_kos' => $data_kos 
        ]);
    }

    /**
     * Menampilkan form tambah Kos baru
     */
    public function create()
    {
        return view('owner.kos.create');
    }

    /**
     * Store (Menyimpan Kos Baru)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'tipe_kos' => 'required|in:pria,wanita,campur',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'latitude' => 'nullable|string|max:100',
            'longitude' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_harga' => 'required|in:month,year',
            'size' => 'nullable|string|max:50',
            'fasilitas_kamar' => 'nullable|string',
            'fasilitas_umum' => 'nullable|string',
            'fasilitas_parkir' => 'nullable|string',
            'peraturan_kos' => 'nullable|string',
        ]);
        
        $ownerId = Auth::id(); 
        
        Kos::create([
            'owner_id' => $ownerId,
            'name' => $validatedData['nama_kos'],
            'address' => $validatedData['alamat'],
            'city' => $validatedData['kota'],
            'kos_type' => $validatedData['tipe_kos'],
            'price' => $validatedData['harga'],
            'price_duration' => $validatedData['durasi_harga'],
            'latitude' => $validatedData['latitude'] ?? null,
            'longitude' => $validatedData['longitude'] ?? null,
            'description' => $validatedData['deskripsi'] ?? null,
            'size' => $validatedData['size'] ?? null,
            'fasilitas_kamar' => $validatedData['fasilitas_kamar'] ?? null,
            'fasilitas_umum' => $validatedData['fasilitas_umum'] ?? null,
            'fasilitas_parkir' => $validatedData['fasilitas_parkir'] ?? null,
            'peraturan_kos' => $validatedData['peraturan_kos'] ?? null,
        ]);

        return redirect('/owner/kos-saya')->with('success', 'Kos baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit Kos
     */
    public function edit($id)
    {
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($id); 
        return view('owner.kos.edit', ['kos' => $kos]);
    }

    /**
     * Update (Mengubah Data Kos)
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'tipe_kos' => 'required|in:pria,wanita,campur',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'latitude' => 'nullable|string|max:100',
            'longitude' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_harga' => 'required|in:month,year',
            'size' => 'nullable|string|max:50',
            'fasilitas_kamar' => 'nullable|string',
            'fasilitas_umum' => 'nullable|string',
            'fasilitas_parkir' => 'nullable|string',
            'peraturan_kos' => 'nullable|string',
        ]);

        $kos = Kos::where('owner_id', Auth::id())->findOrFail($id);

        $kos->update([
            'name' => $validatedData['nama_kos'],
            'address' => $validatedData['alamat'],
            'city' => $validatedData['kota'],
            'kos_type' => $validatedData['tipe_kos'],
            'price' => $validatedData['harga'],
            'price_duration' => $validatedData['durasi_harga'],
            'latitude' => $validatedData['latitude'] ?? null,
            'longitude' => $validatedData['longitude'] ?? null,
            'description' => $validatedData['deskripsi'] ?? null,
            'size' => $validatedData['size'] ?? null,
            'fasilitas_kamar' => $validatedData['fasilitas_kamar'] ?? null,
            'fasilitas_umum' => $validatedData['fasilitas_umum'] ?? null,
            'fasilitas_parkir' => $validatedData['fasilitas_parkir'] ?? null,
            'peraturan_kos' => $validatedData['peraturan_kos'] ?? null,
        ]);

        return redirect('/owner/kos-saya')->with('success', 'Data kos berhasil diperbarui!');
    }
        
    /**
     * Menghapus Kos
     */
    public function destroy($id)
    {
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($id);
        $kos->delete();
        return redirect('/owner/kos-saya')->with('success', 'Data kos berhasil dihapus!');
    }
}