<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verificar si el usuario tiene el rol
        if (!$user->roles()->where('name', $role)->exists()) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => "No tienes permisos para acceder a esta ruta. Se requiere el rol: {$role}"
            ], 403);
        }

        return $next($request);
    }
}
