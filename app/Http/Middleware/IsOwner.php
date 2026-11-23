<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
       
        if (!Auth::check()) {
            return redirect('/login'); 
        }

     
        if (Auth::user()->role !== 'owner') {
            abort(403, 'AKSES DITOLAK: Anda bukan Owner!'); 
        }

        return $next($request);
    }
}