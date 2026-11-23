<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- Import User
use Illuminate\Support\Facades\Hash; // <-- Import Hash

class CreateSuperAdminUser extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Gatot Kaca',
            'email' => 'gatot@pcr.ac.id',
            'password' => Hash::make('gatotkaca'),
            'role' => 'owner', // Saya set 'owner' agar bisa tes login ke dashboard owner
            'phone' => '081234567890' // Tambahkan phone karena di struktur tabel User kamu ada kolom phone (NOT NULL)
        ]);
    }
}