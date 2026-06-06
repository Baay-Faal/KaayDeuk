<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Visite;
use App\Enums\StatutBien;
use App\Enums\StatutVisite;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $biensParStatut = Bien::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')->pluck('total', 'statut')->toArray();

        $biens = [
            'total'       => array_sum($biensParStatut),
            'disponibles' => $biensParStatut[StatutBien::DISPONIBLE->value] ?? 0,
            'reserves'    => $biensParStatut[StatutBien::RESERVE->value]    ?? 0,
            'vendus'      => $biensParStatut[StatutBien::VENDU->value]      ?? 0,
            'loues'       => $biensParStatut[StatutBien::LOUE->value]       ?? 0,
        ];

        $clients = [
            'total'  => Client::count(),
            'actifs' => Client::where('is_active', true)->count(),
        ];

        $stats = Transaction::select(
            DB::raw('count(*) as total'),
            DB::raw('SUM(montant) as chiffre_affaires'),
            DB::raw('SUM(commission_agence) as commissions')
        )->first();

        $transactions = [
            'total'            => $stats->total ?? 0,
            'chiffre_affaires' => $stats->chiffre_affaires ?? 0,
            'commissions'      => $stats->commissions ?? 0,
        ];

        $visitesParStatut = Visite::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')->pluck('total', 'statut')->toArray();

        $totalVisites     = array_sum($visitesParStatut);
        $visitesRealisees = $visitesParStatut[StatutVisite::REALISEE->value] ?? 0;

        $visites = [
            'total'             => $totalVisites,
            'planifiees'        => $visitesParStatut[StatutVisite::PLANIFIEE->value] ?? 0,
            'realisees'         => $visitesRealisees,
            'annulees'          => $visitesParStatut[StatutVisite::ANNULEE->value]   ?? 0,
            'taux_confirmation' => $totalVisites > 0 ? round(($visitesRealisees / $totalVisites) * 100) : 0,
        ];

        $biensPopulaires       = Bien::orderBy('nombre_vues', 'desc')->limit(5)->get();
        $dernieresVisites      = Visite::with(['bien', 'client'])->latest('date_visite')->limit(5)->get();
        $dernieresTransactions = Transaction::with(['bien', 'client'])->latest('date_signature')->limit(5)->get();

        $evolutionMensuelle = Transaction::select(
                DB::raw('MONTH(date_signature) as mois'),
                DB::raw('YEAR(date_signature) as annee'),
                DB::raw('SUM(montant) as total')
            )
            ->where('date_signature', '>=', now()->subMonths(5)->startOfMonth())
            ->whereNotNull('date_signature')
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get();

        return view('dashboard', compact(
            'biens', 'clients', 'transactions', 'visites',
            'biensPopulaires', 'dernieresVisites', 'dernieresTransactions',
            'evolutionMensuelle'
        ));
    }
}