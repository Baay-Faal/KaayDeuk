<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Transaction\StoreTransactionRequest;
use App\Http\Requests\Api\V1\Transaction\UpdateTransactionRequest;
use App\Http\Resources\Api\V1\TransactionResource;
use App\Http\Resources\Api\V1\TransactionCollection;
use App\Models\Transaction;
use App\Enums\TypeTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Transaction::class, 'transaction');
    }

    /**
     * Liste des transactions avec filtres
     */
    public function index(Request $request)
    {
        $transactions = Transaction::paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'meta' => [
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
            ]
        ]);
    }

    /**
     * Créer une nouvelle transaction
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Ajouter l'agent connecté
        $data['agent_id'] = $request->user()->id;

        $transaction = Transaction::create($data);
        $transaction->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'message' => 'Transaction créée avec succès.',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * Afficher les détails d'une transaction
     */
    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        $transaction->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'data' => new TransactionResource($transaction),
        ], 200);
    }

    /**
     * Mettre à jour une transaction
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $transaction->update($request->validated());
        $transaction->load(['bien', 'client', 'agent']);

        return response()->json([
            'success' => true,
            'message' => 'Transaction mise à jour avec succès.',
            'data' => new TransactionResource($transaction),
        ], 200);
    }

    /**
     * Supprimer une transaction (soft delete)
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction supprimée avec succès.',
        ], 200);
    }
}
