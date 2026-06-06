<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Bien\StoreBienRequest;
use App\Http\Requests\Api\V1\Bien\UpdateBienRequest;
use App\Http\Requests\Api\V1\Bien\UploadPhotoRequest;
use App\Http\Resources\Api\V1\BienResource;
use App\Http\Resources\Api\V1\BienCollection;
use App\Models\Bien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BienController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Bien::class, 'bien');
    }

    /**
     * Liste des biens avec filtres et recherche
     */
    public function index(Request $request): BienCollection
    {
        $query = Bien::with(['agent', 'media']);

        // Filtres
        if ($request->filled('type_bien')) {
            $query->where('type_bien', $request->type_bien);
        }

        if ($request->filled('type_transaction')) {
            $query->where('type_transaction', $request->type_transaction);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('ville')) {
            $query->where('ville', 'LIKE', '%' . $request->ville . '%');
        }

        if ($request->filled('quartier')) {
            $query->where('quartier', 'LIKE', '%' . $request->quartier . '%');
        }

        // Recherche par prix
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Recherche par surface
        if ($request->filled('surface_min')) {
            $query->where('surface', '>=', $request->surface_min);
        }

        if ($request->filled('surface_max')) {
            $query->where('surface', '<=', $request->surface_max);
        }

        // Recherche par nombre de chambres
        if ($request->filled('chambres_min')) {
            $query->where('nombre_chambres', '>=', $request->chambres_min);
        }

        // Filtrer par agent (si agent connecté et pas admin)
        $user = $request->user();
        if ($user && $user->isAgent() && !$request->has('all')) {
            $query->where('agent_id', $user->id);
        }

        // Recherche par proximité (géolocalisation)
        if ($request->filled(['latitude', 'longitude'])) {
            $rayon = $request->input('rayon', 5); // 5km par défaut
            $query->proximite(
                $request->latitude,
                $request->longitude,
                $rayon
            );
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        if ($sortBy === 'distance' && !$request->filled(['latitude', 'longitude'])) {
            $sortBy = 'created_at';
        }
        
        if ($sortBy !== 'distance') {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $biens = $query->paginate($perPage);

        return new BienCollection($biens);
    }

    /**
     * Créer un nouveau bien
     */
    public function store(StoreBienRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Ajouter l'agent connecté
        $data['agent_id'] = $request->user()->id;
        
        // Publier automatiquement si admin ou définir comme disponible
        if (!isset($data['statut'])) {
            $data['statut'] = 'disponible';
            $data['date_publication'] = now();
        }

        $bien = Bien::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Bien créé avec succès.',
            'data' => new BienResource($bien->load(['agent', 'media'])),
        ], 201);
    }

    /**
     * Afficher les détails d'un bien
     */
    public function show(Request $request, Bien $bien): JsonResponse
    {
        // Incrémenter le nombre de vues (sauf si c'est l'agent du bien)
        if (!$request->user() || $request->user()->id !== $bien->agent_id) {
            $bien->incrementVues();
        }

        $bien->load(['agent', 'media']);

        return response()->json([
            'success' => true,
            'data' => new BienResource($bien),
        ], 200);
    }

    /**
     * Mettre à jour un bien
     */
    public function update(UpdateBienRequest $request, Bien $bien): JsonResponse
    {
        $bien->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Bien mis à jour avec succès.',
            'data' => new BienResource($bien->fresh(['agent', 'media'])),
        ], 200);
    }

    /**
     * Supprimer un bien (soft delete)
     */
    public function destroy(Bien $bien): JsonResponse
    {
        $bien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bien supprimé avec succès.',
        ], 200);
    }

    /**
     * Uploader des photos pour un bien
     */
    public function uploadPhotos(UploadPhotoRequest $request, Bien $bien): JsonResponse
    {
        $this->authorize('uploadPhoto', $bien);

        $uploadedPhotos = [];

        foreach ($request->file('photos') as $photo) {
            $media = $bien->addMedia($photo)
                ->toMediaCollection('photos');
            
            $uploadedPhotos[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'medium' => $media->getUrl('medium'),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedPhotos) . ' photo(s) uploadée(s) avec succès.',
            'data' => $uploadedPhotos,
        ], 201);
    }

    /**
     * Supprimer une photo
     */
    public function deletePhoto(Request $request, Bien $bien, int $mediaId): JsonResponse
    {
        $this->authorize('uploadPhoto', $bien);

        $media = $bien->getMedia('photos')->where('id', $mediaId)->first();

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Photo introuvable.',
            ], 404);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Photo supprimée avec succès.',
        ], 200);
    }
}