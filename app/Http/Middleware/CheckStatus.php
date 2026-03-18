<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->estatus !== 'activo') {
            if ($request->expectsJson() || $request->is('api/*')) {
                /** @var \App\Models\Usuario $user */
                $user = Auth::user();
                $user->tokens()->delete();
                Auth::logout();
                return response()->json([
                    'message' => 'Tu cuenta ha sido desactivada. Contacta al administrador para más información.'
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador para más información.');
        }

        return $next($request);
    }
}
