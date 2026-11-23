<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tiket Komplain
        Schema::create('complaint_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('ticket_type', ['kos','website']);
            $table->foreignId('kos_id')->nullable()->constrained('kos')->onDelete('cascade');
            $table->foreignId('assigned_to_id')->nullable()->constrained('users'); // Staff/Owner
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['open','in_progress','resolved','closed'])->default('open');
            $table->enum('priority', ['low','medium','high'])->default('medium');
            $table->timestamps();
        });

        // 2. Respon Tiket (Chat di dalam tiket)
        Schema::create('ticket_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('complaint_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->text('message');
            $table->string('attachment_url')->nullable();
            $table->timestamps();
        });

        // 3. Reviews / Ulasan
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('ticket_responses');
        Schema::dropIfExists('complaint_tickets');
    }
};