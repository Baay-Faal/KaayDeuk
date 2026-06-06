<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Connexion d'un utilisateur
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        // Rechercher l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects.',
            ], 401);
        }

        // Vérifier si l'utilisateur est actif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.',
            ], 403);
        }

        // Définir les abilities selon le rôle
        $abilities = $this->getAbilitiesByRole($user->role->value);

        // Créer le token d'authentification
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        // Mettre à jour la dernière connexion
        $user->updateLastLogin();

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Obtenir les abilities selon le rôle
     */
    private function getAbilitiesByRole(string $role): array
    {
        return match($role) {
            'admin' => ['*'],
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