<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;
use Illuminate\Http\Request;

class BienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bien::with(['agent', 'media']);

        if ($request->filled('type_bien'))        $query->where('type_bien', $request->type_bien);
        if ($request->filled('type_transaction')) $query->where('type_transaction', $request->type_transaction);
        if ($request->filled('statut'))           $query->where('statut', $request->statut);
        if ($request->filled('ville'))            $query->where('ville', 'like', '%'.$request->ville.'%');
        if ($request->filled('prix_min'))         $query->where('prix', '>=', $request->prix_min);
        if ($request->filled('prix_max'))         $query->where('prix', '<=', $request->prix_max);

        $biens            = $query->latest()->paginate(12)->withQueryString();
        $typesBien        = TypeBien::cases();
        $typesTransaction = TypeTransaction::cases();
        $statuts          = StatutBien::cases();

        return view('biens.index', compact('biens', 'typesBien', 'typesTransaction', 'statuts'));
    }

    public function show($id)
    {
        $bien = Bien::with(['agent', 'visites.client', 'transactions.client', 'media'])->findOrFail($id);
        return view('biens.show', compact('bien'));
    }
}