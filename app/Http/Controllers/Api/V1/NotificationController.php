<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur connecte
     * GET /api/v1/notifications
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $filtre  = $request->get('filtre', 'toutes'); // toutes | non_lues | lues

        $query = $request->user()->notifications();

        if ($filtre === 'non_lues') {
            $query = $request->user()->unreadNotifications();
        } elseif ($filtre === 'lues') {
            $query = $request->user()->readNotifications();
        }

        $notifications = $query->paginate($perPage);

        $items = $notifications->map(fn($n) => [
            'id'         => $n->id,
            'type'       => class_basename($n->type),
            'data'       => $n->data,
            'lu'         => !is_null($n->read_at),
            'lu_le'      => $n->read_at?->toDateTimeString(),
            'created_at' => $n->created_at->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifications recuperees',
            'data'    => $items,
            'meta'    => [
                'total'        => $notifications->total(),
                'non_lues'     => $request->user()->unreadNotifications()->count(),
                'par_page'     => $notifications->perPage(),
                'page_actuelle' => $notifications->currentPage(),
                'derniere_page' => $notifications->lastPage(),
            ],
        ]);
    }

    /**
     * Marquer une notification comme lue
     * PATCH /api/v1/notifications/{id}/lire
     */
    public function marquerLue(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marquee comme lue',
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     * PATCH /api/v1/notifications/lire-tout
     */
    public function marquerToutesLues(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $count . ' notification(s) marquee(s) comme lues',
            'data'    => ['total_marquees' => $count],
        ]);
    }

    /**
     * Supprimer une notification
     * DELETE /api/v1/notifications/{id}
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimee',
        ]);
    }

    /**
     * Supprimer toutes les notifications lues
     * DELETE /api/v1/notifications/lues
     */
    public function supprimerLues(Request $request): JsonResponse
    {
        $count = $request->user()->readNotifications()->count();
        $request->user()->readNotifications()->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' notification(s) supprimee(s)',
            'data'    => ['total_supprimees' => $count],
        ]);
    }

    /**
     * Compteur de notifications non lues
     * GET /api/v1/notifications/compteur
     */
    public function compteur(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Compteur de notifications',
            'data'    => [
                'non_lues' => $request->user()->unreadNotifications()->count(),
                'total'    => $request->user()->notifications()->count(),
            ],
        ]);
    }
}