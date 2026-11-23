<?php

namespace App\Http\Controllers\Owner; // <-- Pastikan namespace-nya "Owner"

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kamar; // <-- JEMBATAN ke tabel 'kamar'
use App\Models\Kos;   // <-- INI YANG HILANG! JEMBATAN ke tabel 'kos'
use Illuminate\Support\Facades\Auth; // <-- Untuk mengambil ID Owner

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil ID Owner yang sedang login
        // Ganti 1 dengan Auth::id() jika login Anda sudah siap
        $ownerId = 1; // Auth::id(); 

        // 2. Ambil semua kamar
        // Kita gunakan 'with('kos')' agar kita bisa tahu kamar ini milik kos mana
        $semua_kamar = Kamar::with('kos')
                        ->whereHas('kos', function($query) use ($ownerId) {
                            $query->where('owner_id', $ownerId); // Tampilkan kamar milik owner yang login
                        })
                        ->get(); // <-- 'latest()' sudah kita hapus (Perbaikan Error)

        // 3. Kirim data kamar ke view
        return view('owner.kamar.index', [
            'semua_kamar' => $semua_kamar
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Ambil ID Owner
        $ownerId = 1; // Ganti dengan Auth::id() nanti

        // 2. Ambil SEMUA KOS yang dimiliki owner ini
        $daftar_kos = Kos::where('owner_id', $ownerId)->select('id', 'name')->get();

        // 3. Kirim daftar kos itu ke view 'create'
        return view('owner.kamar.create', [
            'daftar_kos' => $daftar_kos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Nanti kita isi ini
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Nanti kita isi ini
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Nanti kita isi ini
    }
}
