<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{
    use HasFactory;

    protected $table = 'ticket_responses';

    protected $fillable = [
        'ticket_id',
        'user_id', // Ini adalah ID pengirim (bisa Pelanggan atau Owner)
        'message',
        'attachment_url',
    ];

    /**
     * Tabel ini hanya punya 'created_at'
     */
    public $timestamps = false;

    /**
     * Relasi: Satu balasan milik satu Tiket Utama
     */
    public function ticket()
    {
        return $this->belongsTo(ComplaintTicket::class, 'ticket_id');
    }

    /**
     * Relasi: Satu balasan dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}