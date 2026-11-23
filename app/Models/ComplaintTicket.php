<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ComplaintTicket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi
    public function user() {
        return $this->belongsTo(User::class); // Pelapor
    }

    public function kos() {
        return $this->belongsTo(Kos::class);
    }

    public function assignedStaff() {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function responses() {
        return $this->hasMany(TicketResponse::class, 'ticket_id');
    }

    /**
     * SCOPE FILTER (Modul 12)
     * Untuk Search & Filter Tiket Komplain
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        // 1. Search: Cari di Judul atau Nama Pelapor
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', '%' . $search . '%');
                  });
            });
        });

        // 2. Filter Kos
        $query->when($filters['kos_id'] ?? false, function($query, $kos_id) {
            return $query->where('kos_id', $kos_id);
        });

        // 3. Filter Status (Open, In Progress, Resolved, Closed)
        $query->when($filters['status'] ?? false, function($query, $status) {
            return $query->where('status', $status);
        });

        // 4. Filter Prioritas (High, Medium, Low)
        $query->when($filters['priority'] ?? false, function($query, $priority) {
            return $query->where('priority', $priority);
        });
    }
}