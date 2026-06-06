<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Visite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    /**
     * Contrat de vente ou de location
     * GET /api/v1/transactions/{transaction}/contrat
     */
    public function contrat(Transaction $transaction): Response
    {
        $transaction->load([
            'bien',
            'client',
            'agent',
        ]);

        $pdf = Pdf::loadView('pdf.contrat', compact('transaction'))
            ->setPaper('a4', 'portrait');

        $filename = 'contrat_' . $transaction->reference . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Rapport de visite
     * GET /api/v1/visites/{visite}/rapport
     */
    public function rapportVisite(Visite $visite): Response
    {
        $visite->load([
            'bien',
            'client',
            'agent',
        ]);

        $pdf = Pdf::loadView('pdf.rapport_visite', compact('visite'))
            ->setPaper('a4', 'portrait');

        $filename = 'rapport_visite_' . $visite->id . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Recu de transaction
     * GET /api/v1/transactions/{transaction}/recu
     */
    public function recu(Transaction $transaction): Response
    {
        $transaction->load([
            'bien',
            'client',
            'agent',
        ]);

        $pdf = Pdf::loadView('pdf.recu', compact('transaction'))
            ->setPaper('a4', 'portrait');

        $filename = 'recu_' . $transaction->reference . '.pdf';

        return $pdf->download($filename);
    }
}