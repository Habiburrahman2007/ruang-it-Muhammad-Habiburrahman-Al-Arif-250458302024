<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (bool) Auth::user()->banned) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah diblokir. Hubungi admin untuk informasi lebih lanjut.'
            ]);
        }

        return $next($request);
    }
}
