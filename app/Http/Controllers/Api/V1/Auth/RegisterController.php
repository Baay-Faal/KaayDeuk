<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'role' => $request->role,
            'is_active' => true,
        ]);

        // Marquer l'email comme vérifié (optionnel pour une API)
        $user->markEmailAsVerified();

        // Définir les abilities selon le rôle
        $abilities = $this->getAbilitiesByRole($user->role->value);

        // Créer le token d'authentification
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie.',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Obtenir les abilities selon le rôle
     */
    private function getAbilitiesByRole(string $role): array
    {
        return match($role) {
            'admin' => ['*'], // Tous les droits
            'agent' => [
                'manage-own-biens',
                'manage-own-clients',
                'manage-own-visites',
                'create-transactions',
                'view-dashboard',
            ],
            'client' => [
                'view-biens',
                'create-favoris',
                'request-visite',
                'view-profile',
            ],
            default => [],
        };
    }
}