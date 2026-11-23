<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Import Middleware (Ini sudah benar di file kamu)
use App\Http\Middleware\CheckIsLogin;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 2. DAFTARKAN ALIAS DI SINI (Bagian ini yang tadi kosong)
        $middleware->alias([
            'checkislogin' => CheckIsLogin::class,
            'checkrole' => CheckRole::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();