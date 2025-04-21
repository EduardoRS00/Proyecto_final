<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica que el usuario esté autenticado y sea administrador
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Si no, lanza error 403
            abort(403, 'Acceso denegado. Solo para administradores.');
        }

        return $next($request);
    }
}
