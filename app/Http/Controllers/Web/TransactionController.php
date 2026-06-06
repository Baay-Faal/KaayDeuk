<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Enums\TypeTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['bien', 'client', 'agent']);

        if ($request->filled('type'))       $query->where('type', $request->type);
        if ($request->filled('date_debut')) $query->where('date_signature', '>=', $request->date_debut);
        if ($request->filled('date_fin'))   $query->where('date_signature', '<=', $request->date_fin);

        $transactions = $query->latest('date_signature')->paginate(15)->withQueryString();
        $types        = TypeTransaction::cases();

        return view('transactions.index', compact('transactions', 'types'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['bien', 'client', 'agent'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }
}