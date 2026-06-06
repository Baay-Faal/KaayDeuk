<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filtre = $request->get('filtre', 'toutes');

        $query = match($filtre) {
            'non_lues' => $request->user()->unreadNotifications(),
            'lues'     => $request->user()->readNotifications(),
            default    => $request->user()->notifications(),
        };

        $notifications = $query->paginate(20)->withQueryString();
        $nonLues       = $request->user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'nonLues', 'filtre'));
    }

    public function compteur(Request $request)
    {
        return response()->json([
            'non_lues' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function marquerLue(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function marquerToutesLues(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}