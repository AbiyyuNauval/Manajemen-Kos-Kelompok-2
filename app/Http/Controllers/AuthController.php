<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Login
    public function index()
    {
        // Jika user sudah login, langsung lempar ke dashboard (agar tidak perlu login lagi)
        if (Auth::check()) {
            if (Auth::user()->role == 'owner') {
                return redirect('/owner/dashboard');
            }
            return redirect('/'); // User biasa ke home
        }

        // Jika belum login, tampilkan form login
        // Lokasi file view sesuai struktur foldermu: resources/views/Login/auth_index.blade.php
        return view('Login.auth_index');
    }

    // 2. Proses Login (Memeriksa Email & Password)
    public function login(Request $request)
    {
        // Validasi inputan wajib diisi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Data login
        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Coba login (Auth::attempt otomatis cek hash password)
        if (Auth::attempt($infologin)) {
            // Kalau sukses login:
            
            // Cek Role (Apakah Owner?)
            if (Auth::user()->role == 'owner') {
                return redirect('/owner/dashboard')->with('success', 'Halo Owner, Selamat Datang!');
            }
            
            // Jika User biasa (atau role lain)
            return redirect('/')->with('success', 'Berhasil Login!');
        } else {
            // Kalau gagal (Email/Password salah)
            return redirect()->back()->withErrors('Email atau Password salah')->withInput();
        }
    }

    // 3. Proses Logout
    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken(); 
        return redirect('/login')->with('success', 'Berhasil Logout');
    }
}