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
        // $this->authorizeResource(Client::class, 'client');
    }

    /**
     * Liste des clients avec filtres
     */
    public function index(Request $request): ClientCollection
    {
        $query = Client::with(['agent']);

        // Filtres
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Recherche par nom/prénom/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Recherche par budget
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        // Filtrer par agent connecté (si agent et pas admin)
        $user = $request->user();
        if ($user && $user->isAgent() && !$request->has('all')) {
            $query->where('agent_id', $user->id);
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
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
        
        // Ajouter l'agent connecté
        $data['agent_id'] = $request->user()->id;

        $client = Client::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Client créé avec succès.',
            'data' => new ClientResource($client->load('agent')),
        ], 201);
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Request $request, Client $client): JsonResponse
    {
        $client->load(['agent']);

        // Charger les favoris si demandé
        if ($request->has('with_favoris')) {
            $client->load('biensFavoris');
        }

        return response()->json([
            'success' => true,
            'data' => new ClientResource($client),
        ], 200);
    }

    /**
     * Mettre à jour un client
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Client mis à jour avec succès.',
            'data' => new ClientResource($client->fresh(['agent'])),
        ], 200);
    }

    /**
     * Supprimer un client (soft delete)
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