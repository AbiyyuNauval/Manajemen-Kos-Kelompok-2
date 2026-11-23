<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\ComplaintTicket;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TesFiturOwnerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CARI OWNER (KYASS)
        // Ganti email ini jika kamu pakai email lain untuk login
        $ownerEmail = 'kyass@gmail.com'; 
        $owner = User::where('email', $ownerEmail)->first();

        if (!$owner) {
            $this->command->error("User dengan email $ownerEmail tidak ditemukan! Pastikan kamu sudah buat usernya.");
            return;
        }

        // 2. CARI KOS MILIK OWNER
        $kos = Kos::where('owner_id', $owner->id)->first();

        if (!$kos) {
            $this->command->error("User $ownerEmail belum punya Kos! Silahkan input Kos dulu lewat dashboard.");
            return;
        }

        $this->command->info("Menambahkan data dummy untuk Kos: " . $kos->name);

        // 3. PASTIKAN ADA KAMAR
        // Kita butuh minimal 1 kamar untuk dibooking
        $kamar = Kamar::where('kos_id', $kos->id)->first();
        if (!$kamar) {
            $kamar = Kamar::create([
                'kos_id' => $kos->id,
                'room_number' => 'A-101 (Auto Generated)',
                'is_available' => 1
            ]);
        }

        // 4. BUAT 3 PENYEWA PALSU (RENTER)
        $penyewa1 = $this->createDummyUser('Budi Santoso', 'budi.dummy@test.com');
        $penyewa2 = $this->createDummyUser('Siti Aminah', 'siti.dummy@test.com');
        $penyewa3 = $this->createDummyUser('Rudi Tabuti', 'rudi.dummy@test.com');

        // ==========================================
        // SKENARIO 1: BOOKING PENDING (Menunggu Konfirmasi)
        // ==========================================
        Booking::create([
            'user_id' => $penyewa1->id,
            'kos_id' => $kos->id,
            'kamar_id' => $kamar->id,
            'start_date' => Carbon::now()->addDays(3), // Masuk 3 hari lagi
            'end_date' => Carbon::now()->addMonth(),
            'status' => 'pending', // <--- INI PENTING
            'payment_type' => 'dp',
            'created_at' => Carbon::now(),
        ]);
        $this->command->info("-> Booking Pending dibuat (oleh Budi)");

        // ==========================================
        // SKENARIO 2: BOOKING ACTIVE + TRANSAKSI (Uang Masuk)
        // ==========================================
        $bookingActive = Booking::create([
            'user_id' => $penyewa2->id,
            'kos_id' => $kos->id,
            'kamar_id' => $kamar->id,
            'start_date' => Carbon::now()->subDays(5), // Sudah masuk 5 hari lalu
            'end_date' => Carbon::now()->addDays(25),
            'status' => 'active', // <--- Sedang ngekos
            'payment_type' => 'lunas',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Buat Transaksi biar Grafik Dashboard NAIK
        Transaction::create([
            'booking_id' => $bookingActive->id,
            'sender_id' => $penyewa2->id,
            'recipient_id' => $owner->id,
            'amount' => 500000, // Rp 500.000
            'type' => 'sewa',
            'status' => 'success', // Uang sudah masuk
            'description' => 'Pembayaran Bulan November',
            'created_at' => Carbon::now(),
        ]);
        $this->command->info("-> Booking Active & Transaksi Rp 500rb dibuat (oleh Siti)");

        // ==========================================
        // SKENARIO 3: BOOKING COMPLETED (Sudah Selesai)
        // ==========================================
        $bookingCompleted = Booking::create([
            'user_id' => $penyewa3->id,
            'kos_id' => $kos->id,
            'kamar_id' => $kamar->id,
            'start_date' => Carbon::now()->subMonths(2),
            'end_date' => Carbon::now()->subMonth(),
            'status' => 'completed', // <--- Sudah checkout
            'payment_type' => 'lunas',
            'created_at' => Carbon::now()->subMonths(2),
        ]);
        
        // Transaksi masa lalu
        Transaction::create([
            'booking_id' => $bookingCompleted->id,
            'sender_id' => $penyewa3->id,
            'recipient_id' => $owner->id,
            'amount' => 1200000, // Rp 1.200.000
            'type' => 'sewa',
            'status' => 'success',
            'created_at' => Carbon::now()->subMonths(2),
        ]);
        $this->command->info("-> Booking Completed & Transaksi Lama dibuat (oleh Rudi)");


        // ==========================================
        // SKENARIO 4: TIKET KOMPLAIN
        // ==========================================
        
        // Kasus 1: Air Mati (OPEN - Belum dibalas)
        ComplaintTicket::create([
            'user_id' => $penyewa2->id, // Siti (yang sedang active)
            'ticket_type' => 'kos',
            'kos_id' => $kos->id,
            'title' => 'Air Kamar Mandi Mati Total',
            'description' => 'Pak, tolong dicek air di kamar saya mati sejak pagi. Saya belum mandi mau kuliah.',
            'status' => 'open', // <--- PENTING
            'priority' => 'high',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        // Kasus 2: AC Bocor (IN PROGRESS - Sedang diperbaiki)
        ComplaintTicket::create([
            'user_id' => $penyewa2->id,
            'ticket_type' => 'kos',
            'kos_id' => $kos->id,
            'title' => 'AC Menetes Air',
            'description' => 'AC netes terus bikin lantai licin.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        $this->command->info("-> 2 Tiket Komplain berhasil dibuat!");
    }

    // Fungsi helper buat bikin user dummy kalau belum ada
    private function createDummyUser($name, $email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('123'),
                'role' => 'user',
                'phone' => '0812' . rand(10000000, 99999999)
            ]);
        }
        return $user;
    }
}