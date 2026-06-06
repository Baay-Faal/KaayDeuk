<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BienResource;
use App\Models\Client;
use App\Models\Bien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    /**
     * Lister les favoris d'un client
     */
    public function index(Client $client): JsonResponse
    {
        $this->authorize('manageFavoris', $client);

        $favoris = $client->biensFavoris()->with(['agent', 'media'])->get();

        return response()->json([
            'success' => true,
            'data' => BienResource::collection($favoris),
        ], 200);
    }

    /**
     * Ajouter un bien aux favoris
     */
    public function store(Request $request, Client $client): JsonResponse
    {
        $this->authorize('manageFavoris', $client);

        $request->validate([
            'bien_id' => ['required', 'exists:biens,id'],
        ]);

        $bien = Bien::findOrFail($request->bien_id);

        // Vérifier si déjà en favoris
        if ($client->aFavori($bien)) {
            return response()->json([
                'success' => false,
                'message' => 'Ce bien est déjà dans les favoris.',
            ], 400);
        }

        $client->ajouterFavori($bien);

        return response()->json([
            'success' => true,
            'message' => 'Bien ajouté aux favoris avec succès.',
        ], 201);
    }

    /**
     * Retirer un bien des favoris
     */
    public function destroy(Client $client, Bien $bien): JsonResponse
    {
        $this->authorize('manageFavoris', $client);

        if (!$client->aFavori($bien)) {
            return response()->json([
                'success' => false,
                'message' => 'Ce bien n\'est pas dans les favoris.',
            ], 404);
        }

        $client->retirerFavori($bien);

        return response()->json([
            'success' => true,
            'message' => 'Bien retiré des favoris avec succès.',
        ], 200);
    }
}