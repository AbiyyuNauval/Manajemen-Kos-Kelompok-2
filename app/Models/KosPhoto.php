<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KosPhoto extends Model
{
    protected $guarded = ['id'];

    public function kos() {
        return $this->belongsTo(Kos::class);
    }
}