<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Visite\StoreVisiteRequest;
use App\Http\Requests\Api\V1\Visite\UpdateVisiteRequest;
use App\Http\Resources\Api\V1\VisiteResource;
use App\Http\Resources\Api\V1\VisiteCollection;
use App\Models\Visite;
use App\Enums\StatutVisite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisiteController extends Controller
{
    /**
     * Liste des visites avec filtres
     */
    public function index(Request $request): VisiteCollection
    {
        $query = Visite::with(['bien', 'client', 'agent']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('bien_id')) {
            $query->where('bien_id', $request->bien_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Filtrer par agent connecté (si agent et pas admin)
        $user = $request->user();
        if ($user && $user->isAgent() && !$request->has('all')) {
            $query->where('agent_id', $user->id);
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('date_visite', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_visite', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->input('sort_by', 'date_visite');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $visites = $query->paginate($perPage);

        return new VisiteCollection($visites);
    }

    /**
     * Créer une nouvelle visite
     */
    public function store(StoreVisiteRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Ajouter l'agent connecté
        $data['agent_id'] = $request->user()->id;

        $visite = Visite::create($data);
        $visite->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'message' => 'Visite créée avec succès.',
            'data' => new VisiteResource($visite),
        ], 201);
    }

    /**
     * Afficher les détails d'une visite
     */
    public function show(Request $request, Visite $visite): JsonResponse
    {
        $visite->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'data' => new VisiteResource($visite),
        ], 200);
    }

    /**
     * Mettre à jour une visite
     */
    public function update(UpdateVisiteRequest $request, Visite $visite): JsonResponse
    {
        $visite->update($request->validated());
        $visite->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'message' => 'Visite mise à jour avec succès.',
            'data' => new VisiteResource($visite),
        ], 200);
    }

    /**
     * Supprimer une visite (soft delete)
     */
    public function destroy(Visite $visite): JsonResponse
    {
        $visite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visite supprimée avec succès.',
        ], 200);
    }

    /**
     * Obtenir les visites planifiées
     */
    public function planifiees(Request $request): VisiteCollection
    {
        $query = Visite::planifiee()->with(['bien', 'client', 'agent']);

        // Filtrer par agent connecté
        $user = $request->user();
        if ($user && $user->isAgent()) {
            $query->where('agent_id', $user->id);
        }

        $visites = $query->orderBy('date_visite', 'asc')->paginate(15);

        return new VisiteCollection($visites);
    }

    /**
     * Marquer une visite comme réalisée
     */
    public function completer(Request $request, Visite $visite): JsonResponse
    {
        $request->validate([
            'rapport' => 'nullable|string',
            'note_client' => 'nullable|integer|min:0|max:5',
            'commentaire_client' => 'nullable|string',
        ]);

        $visite->update([
            'statut' => StatutVisite::REALISEE,
            'rapport' => $request->input('rapport'),
            'note_client' => $request->input('note_client'),
            'commentaire_client' => $request->input('commentaire_client'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visite marquée comme réalisée.',
            'data' => new VisiteResource($visite->fresh(['bien', 'client', 'agent'])),
        ], 200);
    }
}
