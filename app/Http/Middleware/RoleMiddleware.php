<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté a le rôle requis.
     *
     * Usage dans les routes :
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,agent')
     *   ->middleware('role:client')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Non authentifié
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifie.',
                ], 401);
            }
            return redirect()->route('login');
        }

        $rolesAutorises = array_map(fn($r) => trim($r), $roles);

        // Rôle non autorisé
        if (!in_array($user->role->value, $rolesAutorises)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Acces refuse. Role requis : ' . implode(' ou ', $rolesAutorises) . '.',
                    'votre_role' => $user->role->value,
                ], 403);
            }

            // Redirection web selon le rôle
            if ($user->isClient()) {
                return redirect()->route('client.dashboard')
                    ->with('error', 'Acces reserve aux agents et administrateurs.');
            }

            return redirect()->route('web.dashboard')
                ->with('error', 'Acces refuse.');
        }

        return $next($request);
    }
}