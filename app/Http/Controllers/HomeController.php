<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (home).
     */
    public function index()
    {
        $hostsData = [
        'host_pertama' => '127.0.0.1',
        'host_kedua' => 'google.com'
    ];
        // --- DATA SIMULASI ---
        // Anggap saja data ini diambil dari database Anda.
        // Kita siapkan 6 data kos untuk ditampilkan.
        $kostsData = [
            [
                'id' => 1,
                'nama' => 'Kost Apik Tipe A Seturan',
                'tipe' => 'Putri',
                'fasilitas_unggulan' => ['AC', 'Wifi', 'K. Mandi Dalam'],
                'lokasi' => 'Caturtunggal, Yogyakarta',
                'harga_per_bulan' => 1700000,
                'gambar_url' => 'https://images.unsplash.com/photo-1585097495395-6c179221290a?q=80&w=1974&auto=format&fit=crop'
            ],
            [
                'id' => 2,
                'nama' => 'Kost Eksklusif Sudirman Park',
                'tipe' => 'Campur',
                'fasilitas_unggulan' => ['AC', 'Wifi', 'Full Furnished'],
                'lokasi' => 'Tanah Abang, Jakarta Pusat',
                'harga_per_bulan' => 3200000,
                'gambar_url' => 'https://images.unsplash.com/photo-1613553421251-99263024a453?q=80&w=1964&auto=format&fit=crop'
            ],
            [
                'id' => 3,
                'nama' => 'Kos Singgahsini Kencana Loka',
                'tipe' => 'Putra',
                'fasilitas_unggulan' => ['AC', 'Parkir Mobil', 'Akses 24 Jam'],
                'lokasi' => 'Serpong, Tangerang Selatan',
                'harga_per_bulan' => 2100000,
                'gambar_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070&auto=format&fit=crop'
            ],
            [
                'id' => 4,
                'nama' => 'Kost Dago Asri Residence',
                'tipe' => 'Campur',
                'fasilitas_unggulan' => ['Wifi', 'Dapur Bersama', 'K. Mandi Dalam'],
                'lokasi' => 'Coblong, Bandung',
                'harga_per_bulan' => 1950000,
                'gambar_url' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=2070&auto=format&fit=crop'
            ],
            [
                'id' => 5,
                'nama' => 'Kost Griya Gejayan Yogyakarta',
                'tipe' => 'Putri',
                'fasilitas_unggulan' => ['AC', 'Wifi', 'Lemari'],
                'lokasi' => 'Depok, Sleman',
                'harga_per_bulan' => 1650000,
                'gambar_url' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=2070&auto=format&fit=crop'
            ],
            [
                'id' => 6,
                'nama' => 'Kost Minimalis Benhil',
                'tipe' => 'Campur',
                'fasilitas_unggulan' => ['AC', 'Wifi', 'TV'],
                'lokasi' => 'Bendungan Hilir, Jakarta',
                'harga_per_bulan' => 2800000,
                'gambar_url' => 'https://images.unsplash.com/photo-1598228723793-52759bba239c?q=80&w=1974&auto=format&fit=crop'
            ],
        ];

        // Mengirim data ke view 'home.index'
      return view('welcome', [ 'hosts' => $hostsData ]);
    }
}