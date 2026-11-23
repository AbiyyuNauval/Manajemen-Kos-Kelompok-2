<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kos;
use App\Models\KosPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- Wajib ada biar nggak error 'Auth not found'

class KosFotoController extends Controller
{
    /**
     * Menampilkan halaman galeri foto
     */
    public function index($id)
    {
        // KEAMANAN: Pastikan Kos ini milik Owner yang sedang login
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($id);

        // Urutkan: Foto utama paling atas, lalu foto terbaru
        $fotos = KosPhoto::where('kos_id', $id)
                         ->orderBy('is_primary', 'desc') // Foto utama (1) di atas
                         ->latest()
                         ->get();

        return view('owner.foto.index', [
            'kos' => $kos,
            'fotos' => $fotos
        ]);
    }

    /**
     * Menyimpan foto baru (Dengan Auto Primary)
     */
    public function store(Request $request, $id)
    {
        // KEAMANAN: Cek kepemilikan kos sebelum upload
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($id);

        $request->validate([
            'foto' => 'required',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Maks 5MB
            'caption' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                
                // Simpan file ke storage (relative path)
                $path = $file->store('kos_photos', 'public');

                // --- LOGIKA AUTO PRIMARY ---
                // Cek apakah kos ini sudah punya foto utama sebelumnya?
                $adaFotoUtama = KosPhoto::where('kos_id', $id)->where('is_primary', 1)->exists();

                // Jika BELUM ada foto utama, maka foto ini jadi PRIMARY (1)
                // Jika SUDAH ada, maka foto ini jadi biasa (0)
                $statusUtama = !$adaFotoUtama ? 1 : 0;

                KosPhoto::create([
                    'kos_id' => $id,
                    'url' => $path, 
                    'caption' => $request->input('caption'),
                    'is_primary' => $statusUtama, 
                ]);
            }
        }

        return redirect()->back()->with('success', 'Foto berhasil di-upload!');
    }

    /**
     * Menghapus foto spesifik
     */
    public function destroy($id)
    {
        $foto = KosPhoto::findOrFail($id);

        // KEAMANAN: Cek apakah kos dari foto ini milik user yang login
        // Agar user lain tidak bisa menghapus foto milikmu
        $kos = Kos::where('id', $foto->kos_id)
                  ->where('owner_id', Auth::id())
                  ->firstOrFail();
        
        // 1. Hapus file fisik dari storage
        if (Storage::disk('public')->exists($foto->url)) {
            Storage::disk('public')->delete($foto->url);
        }

        // 2. Hapus data dari database
        $foto->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus!');
    }

    /**
     * Menjadikan foto sebagai foto utama
     */
    public function setPrimary($id)
    {
        $foto = KosPhoto::findOrFail($id);

        // KEAMANAN: Cek kepemilikan
        $kos = Kos::where('id', $foto->kos_id)
                  ->where('owner_id', Auth::id())
                  ->firstOrFail();

        // 1. Reset semua foto di kos ini jadi 0 (Biasa)
        KosPhoto::where('kos_id', $kos->id)->update(['is_primary' => 0]);

        // 2. Set foto yang dipilih jadi 1 (Utama)
        $foto->is_primary = 1;
        $foto->save();

        return redirect()->back()->with('success', 'Foto utama berhasil diperbarui!');
    }

    // --- Sisa fungsi resource yang tidak terpakai ---
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}