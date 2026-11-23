<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';
    protected $guarded = ['id'];

    // Relasi
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function photos()
    {
        return $this->hasMany(KosPhoto::class)->orderBy('is_primary', 'desc');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(KosPhoto::class)->where('is_primary', 1);
    }
    
    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'kos_id');
    }

    /**
     * SCOPE FILTER (Modul 12)
     * Search berdasarkan Nama Kos atau Kota
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        });
    }
}