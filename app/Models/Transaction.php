<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    // Izinkan semua kolom diisi kecuali ID (lebih aman & praktis)
    protected $guarded = ['id'];

    // Aktifkan timestamp karena di migrasi kita sudah pakai $table->timestamps()
    // Jika di database kamu memang tidak ada created_at/updated_at, ubah jadi false.
    public $timestamps = true; 

    /**
     * RELASI PENTING (Yang bikin error tadi)
     * Menghubungkan Transaksi ke Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relasi: Pengirim Uang (Penyewa)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi: Penerima Uang (Owner)
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}