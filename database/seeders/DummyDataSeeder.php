<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ComplaintTicket;
use App\Models\Kamar;
use App\Models\Kos;
use App\Models\KosPhoto;
use App\Models\TicketResponse;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVerification;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * DummyDataSeeder
 * ----------------------------------------------------------------------------
 * Mengisi SEMUA tabel aplikasi Manajemen Kos dengan data dummy yang saling
 * nyambung (owner -> kos -> kamar -> booking -> transaksi -> tiket -> ulasan).
 *
 * Cara pakai (database akan DIKOSONGKAN dulu):
 *   php artisan migrate:fresh --seeder=DummyDataSeeder
 *   php artisan storage:link
 *
 * Akun login yang dibuat:
 *   Owner   : gatot@pcr.ac.id      / gatotkaca
 *   Owner   : kyass@gmail.com      / password
 *   Owner   : rina.owner@test.com  / password
 *   Penyewa : penyewa1@test.com ... penyewa40@test.com / password
 */
class DummyDataSeeder extends Seeder
{
    private $faker;

    public function run(): void
    {
        if (Kos::exists()) {
            $this->command->error('Database sudah berisi data kos. Jalankan dengan: php artisan migrate:fresh --seeder=DummyDataSeeder');
            return;
        }

        $this->faker = FakerFactory::create('id_ID');
        $this->faker->seed(2025); // hasil acak selalu sama setiap dijalankan

        $semuaKos = DB::transaction(function () {
            $owners      = $this->buatOwner();
            $penyewa     = $this->buatPenyewa(40);
            $fasilitasId = $this->buatMasterFasilitas();
            $semuaKos    = $this->buatKosDanKamar($owners, $fasilitasId);

            $this->buatBookingDanTransaksi($semuaKos, $penyewa);
            $this->buatTiketKomplain($semuaKos, $penyewa);
            $this->buatUlasan();
            $this->buatVerifikasiUser($penyewa);

            return $semuaKos;
        });

        $this->buatFotoKos($semuaKos);

        $this->command->info('Data dummy berhasil dibuat:');
        $this->command->table(['Tabel', 'Jumlah'], [
            ['users',              User::count()],
            ['kos',                Kos::count()],
            ['kamar',              Kamar::count()],
            ['facilities',         DB::table('facilities')->count()],
            ['kos_facilities',     DB::table('kos_facilities')->count()],
            ['kos_photos',         KosPhoto::count()],
            ['bookings',           Booking::count()],
            ['transactions',       Transaction::count()],
            ['complaint_tickets',  ComplaintTicket::count()],
            ['ticket_responses',   TicketResponse::count()],
            ['reviews',            DB::table('reviews')->count()],
            ['user_verifications', UserVerification::count()],
        ]);
        $this->command->info('Login owner: gatot@pcr.ac.id / gatotkaca');
    }

    // =========================================================================
    // 1. USER
    // =========================================================================

    private function buatOwner(): array
    {
        $data = [
            ['Gatot Kaca',    'gatot@pcr.ac.id',     'gatotkaca', '081234567801'],
            ['Kyass',         'kyass@gmail.com',     'password',  '081234567802'],
            ['Rina Marlina',  'rina.owner@test.com', 'password',  '081234567803'],
        ];

        $owners = [];
        foreach ($data as [$nama, $email, $password, $hp]) {
            $owners[] = User::updateOrCreate(['email' => $email], [
                'name'              => $nama,
                'password'          => Hash::make($password),
                'role'              => 'owner',
                'phone'             => $hp,
                'email_verified_at' => now(),
            ]);
        }

        return $owners;
    }

    private function buatPenyewa(int $jumlah): array
    {
        $passwordHash = Hash::make('password'); // di-hash sekali saja agar cepat
        $penyewa = [];

        for ($i = 1; $i <= $jumlah; $i++) {
            $penyewa[] = User::updateOrCreate(['email' => "penyewa{$i}@test.com"], [
                'name'              => $this->faker->name(),
                'password'          => $passwordHash,
                'role'              => 'user',
                'phone'             => '08' . $this->faker->numerify('##########'),
                'email_verified_at' => now(),
            ]);
        }

        return $penyewa;
    }

    // =========================================================================
    // 2. FASILITAS (master + pivot)
    // =========================================================================

    private function buatMasterFasilitas(): array
    {
        $daftar = [
            'kamar' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kursi', 'Kamar Mandi Dalam', 'Water Heater', 'TV'],
            'umum'  => ['WiFi', 'Dapur Bersama', 'Kulkas Bersama', 'Ruang Tamu', 'Mesin Cuci', 'CCTV', 'Parkir Motor', 'Parkir Mobil'],
        ];

        $hasil = ['kamar' => [], 'umum' => []];
        foreach ($daftar as $tipe => $namaFasilitas) {
            foreach ($namaFasilitas as $nama) {
                $hasil[$tipe][$nama] = DB::table('facilities')->insertGetId([
                    'name'       => $nama,
                    'icon_url'   => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $hasil;
    }

    // =========================================================================
    // 3. KOS + KAMAR
    // =========================================================================

    private function buatKosDanKamar(array $owners, array $fasilitasId): array
    {
        // [owner_index, nama, tipe, kecamatan, alamat, lat, lng, harga, durasi, ukuran, prefix, jumlah_kamar]
        $daftarKos = [
            [0, 'Kos Griya Rumbai Asri',      'pria',   'Rumbai',      'Jl. Umban Sari No. 12',        0.5689, 101.4261, 850000,  'month', '3x4 m', 'A', 10],
            [0, 'Kos Putri Melati Rumbai',    'wanita', 'Rumbai',      'Jl. Yos Sudarso Gg. Melati 5', 0.5734, 101.4302, 950000,  'month', '3x4 m', 'B', 8],
            [0, 'Kos Eksklusif Sudirman',     'campur', 'Sukajadi',    'Jl. Jend. Sudirman No. 210',   0.5163, 101.4481, 2200000, 'month', '4x5 m', 'C', 8],
            [1, 'Kos Panam Indah',            'pria',   'Tampan',      'Jl. HR Soebrantas Gg. Amal 3', 0.4662, 101.3779, 700000,  'month', '3x3 m', 'D', 10],
            [1, 'Kos Putri Bina Widya',       'wanita', 'Tampan',      'Jl. Bina Widya No. 45',        0.4719, 101.3826, 9600000, 'year',  '3x4 m', 'E', 8],
            [2, 'Kos Harmoni Marpoyan',       'campur', 'Marpoyan Damai', 'Jl. Kaharuddin Nasution No. 88', 0.4810, 101.4532, 1250000, 'month', '3x4 m', 'F', 8],
            [2, 'Kos Mawar Bukit Raya',       'wanita', 'Bukit Raya',  'Jl. Arifin Ahmad No. 17',      0.4936, 101.4589, 1100000, 'month', '3x4 m', 'G', 6],
        ];

        $semuaKos = [];
        foreach ($daftarKos as [$o, $nama, $tipe, $kec, $alamat, $lat, $lng, $harga, $durasi, $ukuran, $prefix, $jmlKamar]) {
            $premium = $harga >= 1200000 && $durasi === 'month';

            $fasKamar = $premium
                ? ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kursi', 'Kamar Mandi Dalam', 'Water Heater', 'TV']
                : $this->faker->randomElements(['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kursi', 'Kamar Mandi Dalam'], $this->faker->numberBetween(3, 5));
            $fasUmum = $this->faker->randomElements(['WiFi', 'Dapur Bersama', 'Kulkas Bersama', 'Ruang Tamu', 'Mesin Cuci', 'CCTV'], $this->faker->numberBetween(3, 5));
            $fasParkir = $premium ? ['Parkir Motor', 'Parkir Mobil'] : ['Parkir Motor'];

            $kos = Kos::create([
                'owner_id'         => $owners[$o]->id,
                'name'             => $nama,
                'slug'             => \Illuminate\Support\Str::slug($nama),
                'address'          => "{$alamat}, Kec. {$kec}, Pekanbaru",
                'city'             => 'Pekanbaru',
                'description'      => "{$nama} berada di kawasan {$kec}, dekat kampus, minimarket, dan tempat makan. "
                                    . 'Lingkungan aman dan nyaman, cocok untuk mahasiswa maupun pekerja.',
                'latitude'         => (string) $lat,
                'longitude'        => (string) $lng,
                'kos_type'         => $tipe,
                'price'            => $harga,
                'price_duration'   => $durasi,
                'size'             => $ukuran,
                'fasilitas_kamar'  => implode(', ', $fasKamar),
                'fasilitas_umum'   => implode(', ', $fasUmum),
                'fasilitas_parkir' => implode(', ', $fasParkir),
                'peraturan_kos'    => $this->peraturan($tipe),
                'created_at'       => now()->subMonths(14),
                'updated_at'       => now()->subMonths(14),
            ]);

            // Pivot kos_facilities
            $pivot = [];
            foreach ($fasKamar as $f) {
                $pivot[] = ['kos_id' => $kos->id, 'facility_id' => $fasilitasId['kamar'][$f], 'type' => 'kamar'];
            }
            foreach (array_merge($fasUmum, $fasParkir) as $f) {
                $pivot[] = ['kos_id' => $kos->id, 'facility_id' => $fasilitasId['umum'][$f], 'type' => 'umum'];
            }
            DB::table('kos_facilities')->insert($pivot);

            // Kamar: A-101, A-102, ... (5 kamar per lantai)
            for ($i = 0; $i < $jmlKamar; $i++) {
                $lantai = intdiv($i, 5) + 1;
                $nomor  = ($i % 5) + 1;
                Kamar::create([
                    'kos_id'       => $kos->id,
                    'room_number'  => sprintf('%s-%d%02d', $prefix, $lantai, $nomor),
                    'is_available' => true,
                    'created_at'   => $kos->created_at,
                    'updated_at'   => $kos->created_at,
                ]);
            }

            $semuaKos[] = $kos;
        }

        return $semuaKos;
    }

    private function peraturan(string $tipe): string
    {
        $aturan = [
            'Jam malam pukul 23.00 WIB',
            'Dilarang membawa minuman keras dan narkoba',
            'Wajib menjaga kebersihan kamar dan area bersama',
            'Pembayaran sewa paling lambat tanggal 5 setiap bulan',
            'Tamu wajib lapor ke pengelola',
        ];

        if ($tipe === 'pria') {
            $aturan[] = 'Tamu lawan jenis dilarang masuk kamar';
        } elseif ($tipe === 'wanita') {
            $aturan[] = 'Tamu pria hanya sampai ruang tamu';
        }

        return implode("\n", array_map(fn ($a, $i) => ($i + 1) . '. ' . $a, $aturan, array_keys($aturan)));
    }

    // =========================================================================
    // 4. BOOKING + TRANSAKSI
    // =========================================================================

    private function buatBookingDanTransaksi(array $semuaKos, array $penyewa): void
    {
        // Satu penyewa hanya boleh punya satu booking AKTIF
        $antrianPenyewaAktif = $this->faker->shuffleArray($penyewa);
        $now = Carbon::now();

        foreach ($semuaKos as $kos) {
            $hargaBulanan = $kos->price_duration === 'year' ? $kos->price / 12 : $kos->price;
            $kamarKos = $kos->kamar()->orderBy('room_number')->get();

            foreach ($kamarKos as $kamar) {
                $nasib = $this->faker->randomElement([
                    'aktif', 'aktif', 'aktif', 'aktif', 'aktif', 'aktif',
                    'kosong', 'kosong', 'pending', 'selesai_lalu_kosong',
                ]);

                // --- Riwayat lama (sudah selesai) untuk sebagian kamar ---
                if ($this->faker->boolean(35) || $nasib === 'selesai_lalu_kosong') {
                    $mulai   = $now->copy()->subMonths($this->faker->numberBetween(12, 13))->startOfMonth()->addDays($this->faker->numberBetween(0, 9));
                    $selesai = $mulai->copy()->addMonths(6)->subDay();
                    $this->buatBooking($kos, $kamar, $this->faker->randomElement($penyewa), $mulai, $selesai, 'completed', $hargaBulanan);
                }

                // --- Kondisi kamar saat ini ---
                if ($nasib === 'aktif' && count($antrianPenyewaAktif) > 0) {
                    $mulai   = $now->copy()->subMonths($this->faker->numberBetween(0, 5))->startOfMonth()->addDays($this->faker->numberBetween(0, 9));
                    if ($mulai->isFuture()) {
                        $mulai = $now->copy()->startOfMonth();
                    }
                    $selesai = $mulai->copy()->addMonths($this->faker->randomElement([6, 12]))->subDay();
                    $this->buatBooking($kos, $kamar, array_pop($antrianPenyewaAktif), $mulai, $selesai, 'active', $hargaBulanan);
                    $kamar->update(['is_available' => false]);
                } elseif ($nasib === 'pending') {
                    $mulai   = $now->copy()->addDays($this->faker->numberBetween(3, 25));
                    $selesai = $mulai->copy()->addMonths(6)->subDay();
                    $this->buatBooking($kos, $kamar, $this->faker->randomElement($penyewa), $mulai, $selesai, 'pending', $hargaBulanan);
                }
            }

            // Beberapa booking ditolak / dibatalkan (riwayat saja, tidak mengubah kamar)
            foreach (['rejected', 'cancelled'] as $status) {
                if ($this->faker->boolean(60)) {
                    $mulai = $now->copy()->subDays($this->faker->numberBetween(10, 80));
                    $this->buatBooking($kos, $kamarKos->random(), $this->faker->randomElement($penyewa), $mulai, $mulai->copy()->addMonths(6)->subDay(), $status, $hargaBulanan);
                }
            }
        }
    }

    private function buatBooking(Kos $kos, Kamar $kamar, User $user, Carbon $mulai, Carbon $selesai, string $status, float $hargaBulanan): void
    {
        $dibuatPada = $status === 'pending'
            ? Carbon::now()->subDays($this->faker->numberBetween(0, 4))
            : $mulai->copy()->subDays($this->faker->numberBetween(2, 7));

        $booking = Booking::create([
            'user_id'      => $user->id,
            'kamar_id'     => $kamar->id,
            'kos_id'       => $kos->id,
            'start_date'   => $mulai->toDateString(),
            'end_date'     => $selesai->toDateString(),
            'status'       => $status,
            'payment_type' => $this->faker->randomElement(['dp', 'lunas']),
            'created_at'   => $dibuatPada,
            'updated_at'   => $dibuatPada,
        ]);

        if ($status === 'pending') {
            // Penyewa baru mengajukan, pembayaran DP belum dikonfirmasi
            $this->buatTransaksi($booking, $user, $kos, round($hargaBulanan * 0.5), 'pending', $dibuatPada, 'DP booking kamar ' . $kamar->room_number);
            return;
        }

        if (!in_array($status, ['active', 'completed'])) {
            return; // rejected / cancelled: tanpa transaksi
        }

        // Pembayaran sewa tiap bulan, dari tanggal mulai sampai bulan ini (atau sampai selesai)
        $batas = $status === 'completed' ? $selesai : Carbon::now();
        $tanggalBayar = $mulai->copy();
        $bulanKe = 1;
        while ($tanggalBayar->lte($batas)) {
            $this->buatTransaksi(
                $booking, $user, $kos, round($hargaBulanan), 'success',
                $tanggalBayar->copy()->setTime($this->faker->numberBetween(8, 21), $this->faker->numberBetween(0, 59)),
                "Sewa bulan ke-{$bulanKe} kamar {$kamar->room_number}"
            );
            $tanggalBayar->addMonth();
            $bulanKe++;
        }

        // Sesekali ada pembayaran yang gagal lalu diulang
        if ($this->faker->boolean(15)) {
            $this->buatTransaksi($booking, $user, $kos, round($hargaBulanan), 'failed', $mulai->copy()->subDay(), 'Pembayaran gagal (saldo tidak cukup)');
        }

        // Denda telat bayar untuk sebagian kecil penyewa
        if ($this->faker->boolean(10)) {
            $this->buatTransaksi($booking, $user, $kos, 50000, 'success', $mulai->copy()->addMonth()->addDays(7), 'Denda keterlambatan pembayaran', 'denda');
        }
    }

    private function buatTransaksi(Booking $booking, User $pengirim, Kos $kos, float $jumlah, string $status, Carbon $waktu, string $keterangan, string $tipe = 'sewa'): void
    {
        if ($waktu->isFuture()) {
            if ($tipe === 'denda') {
                return; // denda belum terjadi
            }
            $waktu = Carbon::now()->subMinutes($this->faker->numberBetween(5, 300));
        }

        Transaction::create([
            'booking_id'          => $booking->id,
            'sender_id'           => $pengirim->id,
            'recipient_id'        => $kos->owner_id,
            'amount'              => $jumlah,
            'type'                => $tipe,
            'status'              => $status,
            'payment_gateway_ref' => $status === 'pending' ? null : 'TRX-' . strtoupper($this->faker->bothify('??##??##??')),
            'description'         => $keterangan,
            'created_at'          => $waktu,
            'updated_at'          => $waktu,
        ]);
    }

    // =========================================================================
    // 5. TIKET KOMPLAIN + BALASAN
    // =========================================================================

    private function buatTiketKomplain(array $semuaKos, array $penyewa): void
    {
        $contohKeluhan = [
            ['AC kamar tidak dingin',            'AC di kamar saya sudah 3 hari tidak dingin, hanya keluar angin saja. Mohon dicek teknisi.', 'high'],
            ['Air kamar mandi kecil',            'Aliran air di kamar mandi sangat kecil terutama pagi hari, jadi susah mandi sebelum kuliah.', 'medium'],
            ['WiFi sering putus',                'WiFi kos sering putus-putus dari jam 7 malam, mengganggu saat mengerjakan tugas online.', 'medium'],
            ['Lampu koridor lantai 2 mati',      'Lampu di koridor lantai 2 sudah mati beberapa hari, gelap kalau pulang malam.', 'low'],
            ['Kunci pintu kamar rusak',          'Kunci pintu kamar susah dibuka dan kadang macet. Takut tidak bisa masuk kamar.', 'high'],
            ['Tetangga kamar berisik',           'Kamar sebelah sering memutar musik keras sampai lewat tengah malam.', 'low'],
            ['Atap bocor saat hujan',            'Kalau hujan deras, air menetes dari plafon di dekat lemari. Barang saya jadi basah.', 'high'],
            ['Mesin cuci bersama rusak',         'Mesin cuci bersama tidak mau berputar sejak minggu lalu.', 'medium'],
            ['Tempat sampah jarang diangkut',    'Sampah di depan kos menumpuk dan bau karena sudah lama tidak diangkut.', 'medium'],
            ['Parkiran motor penuh',             'Parkiran motor selalu penuh, motor saya sering harus diparkir di luar pagar.', 'low'],
        ];

        $balasanOwner = [
            'Terima kasih atas laporannya. Kami akan segera mengirim teknisi untuk mengecek.',
            'Baik, sudah kami catat. Mohon ditunggu, akan kami tindak lanjuti hari ini.',
            'Mohon maaf atas ketidaknyamanannya. Besok pagi petugas kami akan datang ke kamar.',
        ];
        $balasanSelesai = 'Perbaikan sudah selesai dilakukan. Silakan dicek kembali, kabari kami jika masih ada kendala.';
        $balasanPenyewa = ['Baik pak/bu, terima kasih.', 'Sudah normal kembali, terima kasih banyak!', 'Oke, saya tunggu ya.'];

        foreach ($semuaKos as $kos) {
            // Pelapor diutamakan penyewa yang punya booking di kos ini
            $pelaporIds = Booking::where('kos_id', $kos->id)->whereIn('status', ['active', 'completed'])->pluck('user_id')->unique()->values()->all();
            if (empty($pelaporIds)) {
                $pelaporIds = [$this->faker->randomElement($penyewa)->id];
            }

            $keluhanKos = $this->faker->randomElements($contohKeluhan, $this->faker->numberBetween(2, 4));
            foreach ($keluhanKos as [$judul, $deskripsi, $prioritas]) {
                $status  = $this->faker->randomElement(['open', 'open', 'in_progress', 'resolved', 'closed']);
                $dibuat  = Carbon::now()->subDays($this->faker->numberBetween(0, 40))->subHours($this->faker->numberBetween(0, 20));
                $pelapor = $this->faker->randomElement($pelaporIds);

                $tiket = ComplaintTicket::create([
                    'user_id'        => $pelapor,
                    'ticket_type'    => 'kos',
                    'kos_id'         => $kos->id,
                    'assigned_to_id' => $kos->owner_id,
                    'title'          => $judul,
                    'description'    => $deskripsi,
                    'status'         => $status,
                    'priority'       => $prioritas,
                    'created_at'     => $dibuat,
                    'updated_at'     => $dibuat,
                ]);

                $waktu = $dibuat->copy();
                if ($status !== 'open') {
                    $this->balas($tiket, $kos->owner_id, $this->faker->randomElement($balasanOwner), $waktu->addHours(2));
                    $this->balas($tiket, $pelapor, $this->faker->randomElement($balasanPenyewa), $waktu->addHours(1));
                }
                if (in_array($status, ['resolved', 'closed'])) {
                    $this->balas($tiket, $kos->owner_id, $balasanSelesai, $waktu->addDay());
                    $this->balas($tiket, $pelapor, 'Sudah normal kembali, terima kasih banyak!', $waktu->addHours(3));
                }
            }
        }

        // Tiket tentang website (tidak terkait kos tertentu)
        foreach ([
            ['Tidak bisa upload bukti pembayaran', 'Saat upload bukti transfer selalu muncul error. Sudah coba dari HP dan laptop.', 'high'],
            ['Saran: tambah filter harga',         'Akan lebih mudah kalau pencarian kos bisa difilter berdasarkan rentang harga.', 'low'],
        ] as [$judul, $deskripsi, $prioritas]) {
            $dibuat = Carbon::now()->subDays($this->faker->numberBetween(1, 20));
            ComplaintTicket::create([
                'user_id'     => $this->faker->randomElement($penyewa)->id,
                'ticket_type' => 'website',
                'kos_id'      => null,
                'title'       => $judul,
                'description' => $deskripsi,
                'status'      => 'open',
                'priority'    => $prioritas,
                'created_at'  => $dibuat,
                'updated_at'  => $dibuat,
            ]);
        }
    }

    private function balas(ComplaintTicket $tiket, int $userId, string $pesan, Carbon $waktu): void
    {
        $waktu = $waktu->isFuture() ? Carbon::now() : $waktu->copy();

        $balasan = TicketResponse::create([
            'ticket_id' => $tiket->id,
            'user_id'   => $userId,
            'message'   => $pesan,
        ]);
        // created_at tidak ada di $fillable TicketResponse, jadi diset terpisah
        $balasan->forceFill(['created_at' => $waktu, 'updated_at' => $waktu])->saveQuietly();
    }

    // =========================================================================
    // 6. ULASAN
    // =========================================================================

    private function buatUlasan(): void
    {
        $komentar = [
            5 => ['Kosnya bersih dan pemiliknya ramah. Recommended!', 'Fasilitas lengkap, WiFi kencang, dekat kampus.', 'Nyaman banget, betah tinggal di sini.'],
            4 => ['Secara umum bagus, cuma parkiran agak sempit.', 'Harga sesuai fasilitas, lingkungan aman.', 'Kamar bersih, air lancar. Mantap.'],
            3 => ['Lumayan, tapi WiFi kadang lambat malam hari.', 'Cukup oke, perbaikan kerusakan agak lama.', 'Standar, sesuai harga.'],
        ];

        $sudah = [];
        $bookings = Booking::whereIn('status', ['active', 'completed'])->get();

        foreach ($bookings as $booking) {
            $kunci = $booking->user_id . '-' . $booking->kos_id;
            if (isset($sudah[$kunci]) || !$this->faker->boolean(60)) {
                continue;
            }
            $sudah[$kunci] = true;

            $rating = $this->faker->randomElement([5, 5, 4, 4, 4, 3]);
            $waktu  = Carbon::parse($booking->start_date)->addMonth();
            if ($waktu->isFuture()) {
                $waktu = Carbon::now()->subDay();
            }

            DB::table('reviews')->insert([
                'user_id'    => $booking->user_id,
                'kos_id'     => $booking->kos_id,
                'rating'     => $rating,
                'comment'    => $this->faker->randomElement($komentar[$rating]),
                'created_at' => $waktu,
                'updated_at' => $waktu,
            ]);
        }
    }

    // =========================================================================
    // 7. VERIFIKASI KTP PENYEWA
    // =========================================================================

    private function buatVerifikasiUser(array $penyewa): void
    {
        foreach ($penyewa as $user) {
            $status = $this->faker->randomElement(['verified', 'verified', 'verified', 'verified', 'verified', 'verified', 'verified', 'pending', 'pending', 'rejected']);

            UserVerification::create([
                'user_id'            => $user->id,
                'id_card_number'     => '1471' . $this->faker->numerify('############'), // NIK dummy (kode Pekanbaru)
                'id_card_photo_url'  => "verifikasi/ktp_user_{$user->id}.jpg",
                'selfie_with_id_url' => "verifikasi/selfie_user_{$user->id}.jpg",
                'status'             => $status,
                'notes'              => $status === 'rejected' ? 'Foto KTP buram, mohon upload ulang.' : null,
                'verified_at'        => $status === 'verified' ? now()->subDays($this->faker->numberBetween(5, 200)) : null,
            ]);
        }
    }

    // =========================================================================
    // 8. FOTO KOS (gambar placeholder dibuat otomatis dengan GD)
    // =========================================================================

    private function buatFotoKos(array $semuaKos): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->command->warn('Extension PHP "gd" tidak aktif, foto kos dilewati. Aktifkan extension=gd di php.ini lalu jalankan ulang jika ingin ada foto.');
            return;
        }

        $warna = [[52, 152, 219], [46, 204, 113], [155, 89, 182], [230, 126, 34], [26, 188, 156], [231, 76, 60], [52, 73, 94]];
        $captions = ['Tampak depan', 'Kamar tidur', 'Kamar mandi'];

        foreach ($semuaKos as $idx => $kos) {
            foreach ($captions as $n => $caption) {
                $path = "kos_photos/dummy_kos{$kos->id}_{$n}.jpg";
                Storage::disk('public')->put($path, $this->gambarPlaceholder($kos->name, $caption, $warna[$idx % count($warna)], $n));

                KosPhoto::create([
                    'kos_id'     => $kos->id,
                    'url'        => $path,
                    'caption'    => $caption,
                    'is_primary' => $n === 0,
                ]);
            }
        }
    }

    private function gambarPlaceholder(string $judul, string $subjudul, array $rgb, int $variasi): string
    {
        $w = 800;
        $h = 500;
        $img = imagecreatetruecolor($w, $h);

        // Gradasi vertikal
        for ($y = 0; $y < $h; $y++) {
            $t = $y / $h;
            $gelap = 0.55 + 0.1 * $variasi;
            $c = imagecolorallocate(
                $img,
                (int) ($rgb[0] * (1 - $t * $gelap)),
                (int) ($rgb[1] * (1 - $t * $gelap)),
                (int) ($rgb[2] * (1 - $t * $gelap))
            );
            imageline($img, 0, $y, $w, $y, $c);
        }

        // Ikon rumah sederhana
        $putih = imagecolorallocate($img, 255, 255, 255);
        imagefilledpolygon($img, [400, 110, 310, 190, 490, 190], $putih);
        imagefilledrectangle($img, 330, 190, 470, 280, $putih);
        imagefilledrectangle($img, 385, 225, 415, 280, imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]));

        // Teks (font bawaan GD, diperbesar 2x agar terbaca)
        $this->tulisTengah($img, $judul, 330, $putih, 2);
        $this->tulisTengah($img, $subjudul, 385, $putih, 1);

        ob_start();
        imagejpeg($img, null, 85);
        imagedestroy($img);

        return ob_get_clean();
    }

    private function tulisTengah($img, string $teks, int $y, int $warna, int $skala): void
    {
        $font = 5;
        $tw = imagefontwidth($font) * strlen($teks);
        $th = imagefontheight($font);

        $tmp = imagecreatetruecolor($tw, $th);
        imagesavealpha($tmp, true);
        imagefill($tmp, 0, 0, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
        imagestring($tmp, $font, 0, 0, $teks, imagecolorallocate($tmp, 255, 255, 255));

        $dw = $tw * $skala;
        $dh = $th * $skala;
        imagecopyresampled($img, $tmp, (int) ((imagesx($img) - $dw) / 2), $y, 0, 0, $dw, $dh, $tw, $th);
        imagedestroy($tmp);
    }
}
