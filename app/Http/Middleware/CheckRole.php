<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role && !($role === 'kasir' && auth()->user()->role === 'admin')) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
