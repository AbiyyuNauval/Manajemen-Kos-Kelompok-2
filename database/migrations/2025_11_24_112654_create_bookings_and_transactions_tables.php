<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kamar_id')->constrained('kamar');
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending','active','completed','rejected','cancelled'])->default('pending');
            $table->enum('payment_type', ['dp','lunas']);
            $table->timestamps();
        });

        // 2. Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('recipient_id')->constrained('users');
            $table->decimal('amount', 13, 2);
            $table->enum('type', ['sewa','biaya_admin','refund','denda']);
            $table->enum('status', ['pending','success','failed'])->default('pending');
            $table->string('payment_gateway_ref')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('bookings');
    }
};