<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $guarded = ['id'];

    /**
     * Relasi ke Kos
     */
    public function kos() {
        return $this->belongsTo(Kos::class);
    }

    /**
     * SCOPE FILTER (Modul 12)
     * Fungsi ini menangani Search & Filter secara otomatis
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        // 1. Logic Search (Berdasarkan Nomor Kamar)
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('room_number', 'like', '%' . $search . '%');
        });

        // 2. Logic Filter Berdasarkan Kos (Dropdown Kos)
        $query->when($filters['kos_id'] ?? false, function($query, $kos_id) {
            return $query->where('kos_id', $kos_id);
        });

        // 3. Logic Filter Status (Tersedia / Penuh)
        $query->when(isset($filters['status']), function($query) use ($filters) {
            if($filters['status'] == 'tersedia') {
                return $query->where('is_available', 1);
            } elseif($filters['status'] == 'penuh') {
                return $query->where('is_available', 0);
            }
        });
    }
}