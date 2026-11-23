<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Owner (User)
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            
            // Informasi Dasar
            $table->string('name');
            $table->string('slug')->nullable(); // Untuk link URL cantik
            $table->text('address');
            $table->string('city');
            $table->text('description')->nullable();
            
            // Lokasi (Sesuai Model kamu)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Spesifikasi & Harga
            $table->string('kos_type');         // Putra/Putri/Campur
            $table->double('price');            // Harga
            $table->string('price_duration');   // Per bulan/tahun
            $table->string('size')->nullable(); // Ukuran kamar

            // Fasilitas (Sesuai Model kamu - Pakai text agar muat banyak)
            $table->text('fasilitas_kamar')->nullable();
            $table->text('fasilitas_umum')->nullable();
            $table->text('fasilitas_parkir')->nullable();
            $table->text('peraturan_kos')->nullable();

            $table->timestamps(); // Created_at & Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};