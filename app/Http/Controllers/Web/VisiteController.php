<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Visite;
use App\Enums\StatutVisite;
use Illuminate\Http\Request;

class VisiteController extends Controller
{
    public function index(Request $request)
    {
        $query = Visite::with(['bien', 'client', 'agent']);

        if ($request->filled('statut'))     $query->where('statut', $request->statut);
        if ($request->filled('date_debut')) $query->where('date_visite', '>=', $request->date_debut);
        if ($request->filled('date_fin'))   $query->where('date_visite', '<=', $request->date_fin);

        $visites = $query->orderBy('date_visite', 'desc')->paginate(15)->withQueryString();
        $statuts = StatutVisite::cases();

        return view('visites.index', compact('visites', 'statuts'));
    }

    public function show($id)
    {
        $visite = Visite::with(['bien', 'client', 'agent'])->findOrFail($id);
        return view('visites.show', compact('visite'));
    }
}