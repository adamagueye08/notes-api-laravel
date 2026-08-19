<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Autorise l'accès si l'utilisateur connecté a l'un des rôles passés en paramètre.
     * Usage dans les routes : ->middleware('role:admin,professeur')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            return response()->json([
                'message' => 'Accès non autorisé pour ce rôle.',
            ], 403);
        }

        return $next($request);
    }
}
