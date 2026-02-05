<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        // Check if user has admin flag or admin role
        // Checking for common admin role names just in case
        if ($user->es_admin || $user->tieneRol('admin') || $user->tieneRol('administrador')) {
             return $next($request);
        }

        return redirect()->route('inicio')->with('error', 'No tienes acceso a este sitio');
    }
}
