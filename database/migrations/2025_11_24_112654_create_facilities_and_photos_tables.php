<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Fasilitas
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('icon_url')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Pivot Kos <-> Fasilitas
        Schema::create('kos_facilities', function (Blueprint $table) {
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->enum('type', ['umum', 'kamar'])->default('umum');
            $table->primary(['kos_id', 'facility_id']); // Composite Key
        });

        // 3. Tabel Foto Kos
        Schema::create('kos_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->string('url');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos_photos');
        Schema::dropIfExists('kos_facilities');
        Schema::dropIfExists('facilities');
    }
};