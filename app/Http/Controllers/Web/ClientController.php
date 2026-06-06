<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('agent');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%'.$request->search.'%')
                  ->orWhere('prenom', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('telephone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('is_active')) $query->where('is_active', $request->is_active);

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function show($id)
    {
        $client = Client::with(['agent', 'visites.bien', 'transactions.bien', 'biensFavoris'])->findOrFail($id);
        return view('clients.show', compact('client'));
    }
}