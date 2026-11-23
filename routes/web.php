<?php

use Illuminate\Support\Facades\Route;

// Import Controller Umum & Auth
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;

// Import Controller Khusus Owner
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\KosController;
use App\Http\Controllers\Owner\KamarController;
use App\Http\Controllers\Owner\KosFotoController;
use App\Http\Controllers\Owner\BookingController;
use App\Http\Controllers\Owner\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. RUTE PUBLIK (Bisa diakses siapa saja)
Route::get('/', [HomeController::class, 'index']);

// 2. RUTE OTENTIKASI (Login & Logout)
Route::get('/login', [AuthController::class, 'index'])->name('login'); // Form Login
Route::post('/login', [AuthController::class, 'login']);               // Proses Login
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Proses Logout

// 3. RUTE KHUSUS OWNER
// Menggunakan Middleware Custom sesuai Modul 11:
// - checkislogin : Cek apakah sudah login
// - checkrole:owner : Cek apakah jabatannya 'owner'
Route::prefix('owner')->middleware(['checkislogin', 'checkrole:owner'])->group(function () {
    
    // Dashboard Owner
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Manajemen Kos (CRUD)
    Route::get('/kos-saya', [KosController::class, 'index']);
    Route::get('/kos/create', [KosController::class, 'create']);
    Route::post('/kos', [KosController::class, 'store']);
    Route::get('/kos/{id}/edit', [KosController::class, 'edit']);
    Route::put('/kos/{id}', [KosController::class, 'update']);
    Route::delete('/kos/{id}', [KosController::class, 'destroy']);

    // Manajemen Kamar (CRUD)
    Route::get('/kamar', [KamarController::class, 'index']);
    Route::get('/kamar/create', [KamarController::class, 'create']);
    Route::post('/kamar', [KamarController::class, 'store']);
    Route::get('/kamar/{id}/edit', [KamarController::class, 'edit']);
    Route::put('/kamar/{id}', [KamarController::class, 'update']);
    Route::delete('/kamar/{id}', [KamarController::class, 'destroy']);

    // Manajemen Foto Kos
    Route::get('/kos/{id}/foto', [KosFotoController::class, 'index']);
    Route::post('/kos/{id}/foto', [KosFotoController::class, 'store']);
    Route::delete('/foto/{id}', [KosFotoController::class, 'destroy']);
    Route::put('/foto/{id}/set-primary', [KosFotoController::class, 'setPrimary']);

    // Manajemen Booking / Sewa
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/booking/{id}', [BookingController::class, 'show']);
    Route::put('/booking/{id}', [BookingController::class, 'update']);
    Route::delete('/booking/{id}', [BookingController::class, 'destroy']);

    // Manajemen Tiket Komplain
    Route::get('/tiket', [TicketController::class, 'index']);
    Route::get('/tiket/{id}', [TicketController::class, 'show']);
    Route::post('/tiket/{id}/reply', [TicketController::class, 'store']);
    Route::put('/tiket/{id}/status', [TicketController::class, 'update']);
    Route::delete('/tiket/{id}', [TicketController::class, 'destroy']);
});