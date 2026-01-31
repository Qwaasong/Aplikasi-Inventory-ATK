<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role === $role) {
            return $next($request);
        }

        // Jika user memiliki role yang berbeda, arahkan ke dashboard masing-masing
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.barang'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default => redirect()->route('login')->with('error', 'Unauthorized access.'),
        };
    }
}
