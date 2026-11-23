<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    // Relasi
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function kos() {
        return $this->belongsTo(Kos::class);
    }
    public function kamar() {
        return $this->belongsTo(Kamar::class);
    }
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    /**
     * SCOPE FILTER (Modul 12)
     * Menangani Search & Filter Booking
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        // 1. Search: Cari berdasarkan Nama Penyewa (User)
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        });

        // 2. Filter: Berdasarkan Kos
        $query->when($filters['kos_id'] ?? false, function($query, $kos_id) {
            return $query->where('kos_id', $kos_id);
        });

        // 3. Filter: Berdasarkan Status (Opsional, karena kita sudah pisah tab)
        $query->when($filters['status'] ?? false, function($query, $status) {
            return $query->where('status', $status);
        });
    }
}