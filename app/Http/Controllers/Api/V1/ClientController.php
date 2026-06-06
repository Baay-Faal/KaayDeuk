<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\StoreClientRequest;
use App\Http\Requests\Api\V1\Client\UpdateClientRequest;
use App\Http\Resources\Api\V1\ClientResource;
use App\Http\Resources\Api\V1\ClientCollection;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        // ✅ Policy activée — applique ClientPolicy sur toutes les méthodes
        $this->authorizeResource(Client::class, 'client');
    }

    /**
     * Liste des clients avec filtres
     */
    public function index(Request $request): ClientCollection
    {
        $query = Client::with(['agent']);

        // Filtrer par agent connecté (si agent et pas admin)
        // ✅ Un agent ne voit QUE ses propres clients
        $user = $request->user();
        if ($user && $user->isAgent() && !$request->has('all')) {
            $query->where('agent_id', $user->id);
        }

        // Filtres
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('agent_id') && $user->isAdmin()) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        // Tri
        $sortBy    = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->input('per_page', 15);
        $clients = $query->paginate($perPage);

        return new ClientCollection($clients);
    }

    /**
     * Créer un nouveau client
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $data = $request->validated();

        // L'agent connecté est automatiquement assigné
        $data['agent_id'] = $request->user()->id;

        $client = Client::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Client créé avec succès.',
            'data'    => new ClientResource($client->load('agent')),
        ], 201);
    }

    /**
     * Afficher les détails d'un client
     * ✅ ClientPolicy::view() vérifie que l'agent est bien le référent
     */
    public function show(Request $request, Client $client): JsonResponse
    {
        $client->load(['agent']);

        if ($request->has('with_favoris')) {
            $client->load('biensFavoris');
        }

        return response()->json([
            'success' => true,
            'data'    => new ClientResource($client),
        ], 200);
    }

    /**
     * Mettre à jour un client
     * ✅ ClientPolicy::update() vérifie que l'agent est bien le référent
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Client mis à jour avec succès.',
            'data'    => new ClientResource($client->fresh(['agent'])),
        ], 200);
    }

    /**
     * Supprimer un client
     * ✅ ClientPolicy::delete() vérifie que l'agent est bien le référent
     */
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client supprimé avec succès.',
        ], 200);
    }
}