<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->string('room_number', 50);
            $table->boolean('is_available')->default(true);
            $table->timestamps(); // Created at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};