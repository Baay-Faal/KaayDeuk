<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\DashboardPeriodeRequest;
use App\Models\Bien;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Visite;
use App\Enums\StatutBien;
use App\Enums\StatutVisite;
use App\Enums\TypeTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Vue d'ensemble – KPIs globaux
     * GET /api/v1/dashboard/overview
     */
    public function overview(): JsonResponse
    {
        $biensParStatut = Bien::query()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        $totalBiens       = array_sum($biensParStatut);
        $biensDisponibles = $biensParStatut[StatutBien::DISPONIBLE->value] ?? 0;
        $biensReserves    = $biensParStatut[StatutBien::RESERVE->value]    ?? 0;
        $biensVendus      = $biensParStatut[StatutBien::VENDU->value]      ?? 0;
        $biensLoues       = $biensParStatut[StatutBien::LOUE->value]       ?? 0;
        $biensArchives    = $biensParStatut[StatutBien::ARCHIVE->value]    ?? 0;

        $totalClients  = Client::count();
        $clientsActifs = Client::where('is_active', true)->count();
        $budgetMoyen   = Client::whereNotNull('budget_max')
            ->avg(DB::raw('(budget_min + budget_max) / 2')) ?? 0;

        $statsTransactions = Transaction::query()
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires'),
                DB::raw('SUM(commission_agence) as total_commissions'),
                DB::raw('AVG(montant) as montant_moyen')
            )
            ->first();

        $statsVentes = Transaction::where('type', TypeTransaction::VENTE->value)
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires')
            )
            ->first();

        $statsLocations = Transaction::where('type', TypeTransaction::LOCATION->value)
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires')
            )
            ->first();

        $visitesParStatut = Visite::query()
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        $totalVisites      = array_sum($visitesParStatut);
        $visitesPlanifiees = $visitesParStatut[StatutVisite::PLANIFIEE->value] ?? 0;
        $visitesRealisees  = $visitesParStatut[StatutVisite::REALISEE->value]  ?? 0;
        $visitesAnnulees   = $visitesParStatut[StatutVisite::ANNULEE->value]   ?? 0;

        $tauxConfirmation = $totalVisites > 0
            ? round(($visitesRealisees / $totalVisites) * 100, 1)
            : 0;

        $noteMoyenneVisites = Visite::where('statut', StatutVisite::REALISEE->value)
            ->whereNotNull('note_client')
            ->avg('note_client');

        $biensPopulaires = Bien::orderBy('nombre_vues', 'desc')
            ->limit(5)
            ->get(['id', 'reference', 'titre', 'quartier', 'ville', 'nombre_vues', 'statut', 'prix']);

        $data = [
            'biens' => [
                'total'       => $totalBiens,
                'disponibles' => $biensDisponibles,
                'reserves'    => $biensReserves,
                'vendus'      => $biensVendus,
                'loues'       => $biensLoues,
                'archives'    => $biensArchives,
            ],
            'clients' => [
                'total'        => $totalClients,
                'actifs'       => $clientsActifs,
                'inactifs'     => $totalClients - $clientsActifs,
                'budget_moyen' => round($budgetMoyen),
            ],
            'transactions' => [
                'total'             => $statsTransactions->total ?? 0,
                'chiffre_affaires'  => round($statsTransactions->chiffre_affaires ?? 0),
                'total_commissions' => round($statsTransactions->total_commissions ?? 0),
                'montant_moyen'     => round($statsTransactions->montant_moyen ?? 0),
                'ventes' => [
                    'total'            => $statsVentes->total ?? 0,
                    'chiffre_affaires' => round($statsVentes->chiffre_affaires ?? 0),
                ],
                'locations' => [
                    'total'            => $statsLocations->total ?? 0,
                    'chiffre_affaires' => round($statsLocations->chiffre_affaires ?? 0),
                ],
            ],
            'visites' => [
                'total'             => $totalVisites,
                'planifiees'        => $visitesPlanifiees,
                'realisees'         => $visitesRealisees,
                'annulees'          => $visitesAnnulees,
                'taux_confirmation' => $tauxConfirmation,
                'note_moyenne'      => $noteMoyenneVisites ? round($noteMoyenneVisites, 1) : null,
            ],
            'biens_populaires' => $biensPopulaires->map(fn($b) => [
                'id'           => $b->id,
                'reference'    => $b->reference,
                'titre'        => $b->titre,
                'quartier'     => $b->quartier,
                'ville'        => $b->ville,
                'nombre_vues'  => $b->nombre_vues,
                'statut'       => $b->statut->value,
                'statut_label' => $b->statut->label(),
                'prix'         => round($b->prix),
            ]),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Vue d\'ensemble du tableau de bord',
            'data'    => $data,
        ]);
    }

    /**
     * Statistiques financières par période
     * GET /api/v1/dashboard/transactions
     */
    public function transactions(DashboardPeriodeRequest $request): JsonResponse
    {
        $dateDebut = $request->date_debut
            ? \Carbon\Carbon::parse($request->date_debut)->startOfDay()
            : now()->startOfMonth();

        $dateFin = $request->date_fin
            ? \Carbon\Carbon::parse($request->date_fin)->endOfDay()
            : now()->endOfMonth();

        $totaux = Transaction::whereBetween('date_signature', [$dateDebut, $dateFin])
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires'),
                DB::raw('SUM(commission_agence) as commissions_agence'),
                DB::raw('SUM(commission_agent) as commissions_agents'),
                DB::raw('AVG(montant) as montant_moyen')
            )
            ->first();

        $parType = Transaction::whereBetween('date_signature', [$dateDebut, $dateFin])
            ->select(
                'type',
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires'),
                DB::raw('SUM(commission_agence) as commissions')
            )
            ->groupBy('type')
            ->get()
            ->mapWithKeys(fn($row) => [
                $row->type->value => [
                    'total'            => $row->total,
                    'chiffre_affaires' => round($row->chiffre_affaires),
                    'commissions'      => round($row->commissions),
                ]
            ]);

        $evolutionMensuelle = Transaction::select(
                DB::raw('YEAR(date_signature) as annee'),
                DB::raw('MONTH(date_signature) as mois'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(montant) as chiffre_affaires'),
                DB::raw('SUM(commission_agence) as commissions')
            )
            ->where('date_signature', '>=', now()->subMonths(11)->startOfMonth())
            ->whereNotNull('date_signature')
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get()
            ->map(fn($row) => [
                'periode'          => sprintf('%04d-%02d', $row->annee, $row->mois),
                'total'            => $row->total,
                'chiffre_affaires' => round($row->chiffre_affaires),
                'commissions'      => round($row->commissions),
            ]);

        $topAgents = Transaction::whereBetween('date_signature', [$dateDebut, $dateFin])
            ->select(
                'agent_id',
                DB::raw('count(*) as total_transactions'),
                DB::raw('SUM(montant) as chiffre_affaires'),
                DB::raw('SUM(commission_agent) as commissions')
            )
            ->with('agent:id,name,email')
            ->groupBy('agent_id')
            ->orderByDesc('chiffre_affaires')
            ->limit(5)
            ->get()
            ->map(fn($row) => [
                'agent' => [
                    'id'    => $row->agent?->id,
                    'name'  => $row->agent?->name,
                    'email' => $row->agent?->email,
                ],
                'total_transactions' => $row->total_transactions,
                'chiffre_affaires'   => round($row->chiffre_affaires),
                'commissions'        => round($row->commissions),
            ]);

        $data = [
            'periode' => [
                'debut' => $dateDebut->toDateString(),
                'fin'   => $dateFin->toDateString(),
            ],
            'totaux' => [
                'total'              => $totaux->total ?? 0,
                'chiffre_affaires'   => round($totaux->chiffre_affaires ?? 0),
                'commissions_agence' => round($totaux->commissions_agence ?? 0),
                'commissions_agents' => round($totaux->commissions_agents ?? 0),
                'montant_moyen'      => round($totaux->montant_moyen ?? 0),
            ],
            'par_type'            => $parType,
            'evolution_mensuelle' => $evolutionMensuelle,
            'top_agents'          => $topAgents,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des transactions',
            'data'    => $data,
        ]);
    }

    /**
     * Statistiques du parc immobilier
     * GET /api/v1/dashboard/biens
     */
    public function biens(): JsonResponse
    {
        // Eloquent caste automatiquement les Enums — pas besoin de ::from()
        $parType = Bien::select(
                'type_bien',
                DB::raw('count(*) as total'),
                DB::raw('AVG(prix) as prix_moyen'),
                DB::raw('AVG(surface) as surface_moyenne')
            )
            ->groupBy('type_bien')
            ->get()
            ->map(fn($row) => [
                'type'            => $row->type_bien->value,
                'label'           => $row->type_bien->label(),
                'total'           => $row->total,
                'prix_moyen'      => round($row->prix_moyen),
                'surface_moyenne' => round($row->surface_moyenne, 1),
            ]);

        $parTypeTransaction = Bien::select(
                'type_transaction',
                DB::raw('count(*) as total'),
                DB::raw('AVG(prix) as prix_moyen')
            )
            ->groupBy('type_transaction')
            ->get()
            ->map(fn($row) => [
                'type'       => $row->type_transaction->value,
                'label'      => $row->type_transaction->label(),
                'total'      => $row->total,
                'prix_moyen' => round($row->prix_moyen),
            ]);

        $parStatut = Bien::select(
                'statut',
                DB::raw('count(*) as total')
            )
            ->groupBy('statut')
            ->get()
            ->map(fn($row) => [
                'statut' => $row->statut->value,
                'label'  => $row->statut->label(),
                'color'  => $row->statut->color(),
                'total'  => $row->total,
            ]);

        $parQuartier = Bien::select(
                'quartier',
                'ville',
                DB::raw('count(*) as total'),
                DB::raw('AVG(prix) as prix_moyen'),
                DB::raw('SUM(CASE WHEN statut = "disponible" THEN 1 ELSE 0 END) as disponibles')
            )
            ->whereNotNull('quartier')
            ->groupBy('quartier', 'ville')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($row) => [
                'quartier'    => $row->quartier,
                'ville'       => $row->ville,
                'total'       => $row->total,
                'disponibles' => $row->disponibles,
                'prix_moyen'  => round($row->prix_moyen),
            ]);

        $statsGlobales = Bien::select(
                DB::raw('count(*) as total'),
                DB::raw('AVG(prix) as prix_moyen'),
                DB::raw('MIN(prix) as prix_min'),
                DB::raw('MAX(prix) as prix_max'),
                DB::raw('AVG(surface) as surface_moyenne'),
                DB::raw('SUM(nombre_vues) as vues_totales')
            )
            ->first();

        $biensRecents = Bien::where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($row) => [
                'date'  => $row->date,
                'total' => $row->total,
            ]);

        $caracteristiques = [
            'meubles'    => Bien::where('meuble', true)->count(),
            'climatises' => Bien::where('climatise', true)->count(),
            'securises'  => Bien::where('securise', true)->count(),
        ];

        $data = [
            'statistiques_globales' => [
                'total'           => $statsGlobales->total ?? 0,
                'prix_moyen'      => round($statsGlobales->prix_moyen ?? 0),
                'prix_min'        => round($statsGlobales->prix_min ?? 0),
                'prix_max'        => round($statsGlobales->prix_max ?? 0),
                'surface_moyenne' => round($statsGlobales->surface_moyenne ?? 1),
                'vues_totales'    => $statsGlobales->vues_totales ?? 0,
            ],
            'par_type'             => $parType,
            'par_type_transaction' => $parTypeTransaction,
            'par_statut'           => $parStatut,
            'par_quartier'         => $parQuartier,
            'ajouts_recents_30j'   => $biensRecents,
            'caracteristiques'     => $caracteristiques,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistiques du parc immobilier',
            'data'    => $data,
        ]);
    }

    /**
     * Statistiques des visites
     * GET /api/v1/dashboard/visites
     */
    public function visites(DashboardPeriodeRequest $request): JsonResponse
    {
        $dateDebut = $request->date_debut
            ? \Carbon\Carbon::parse($request->date_debut)->startOfDay()
            : now()->startOfMonth();

        $dateFin = $request->date_fin
            ? \Carbon\Carbon::parse($request->date_fin)->endOfDay()
            : now()->endOfMonth();

        $totaux = Visite::whereBetween('date_visite', [$dateDebut, $dateFin])
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN statut = "planifiee" THEN 1 ELSE 0 END) as planifiees'),
                DB::raw('SUM(CASE WHEN statut = "realisee" THEN 1 ELSE 0 END) as realisees'),
                DB::raw('SUM(CASE WHEN statut = "annulee" THEN 1 ELSE 0 END) as annulees'),
                DB::raw('AVG(CASE WHEN note_client IS NOT NULL THEN note_client END) as note_moyenne')
            )
            ->first();

        $total     = $totaux->total ?? 0;
        $realisees = $totaux->realisees ?? 0;

        $tauxConfirmation = $total > 0 ? round(($realisees / $total) * 100, 1) : 0;
        $tauxAnnulation   = $total > 0 ? round((($totaux->annulees ?? 0) / $total) * 100, 1) : 0;

        $evolutionQuotidienne = Visite::whereBetween('date_visite', [$dateDebut, $dateFin])
            ->select(
                'date_visite',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN statut = "realisee" THEN 1 ELSE 0 END) as realisees'),
                DB::raw('SUM(CASE WHEN statut = "annulee" THEN 1 ELSE 0 END) as annulees')
            )
            ->groupBy('date_visite')
            ->orderBy('date_visite')
            ->get()
            ->map(fn($row) => [
                'date'      => $row->date_visite->toDateString(),
                'total'     => $row->total,
                'realisees' => $row->realisees,
                'annulees'  => $row->annulees,
            ]);

        $parJourSemaine = Visite::select(
                DB::raw('DAYOFWEEK(date_visite) as jour'),
                DB::raw('count(*) as total')
            )
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->map(fn($row) => [
                'jour'  => $row->jour,
                'label' => ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][$row->jour - 1],
                'total' => $row->total,
            ]);

        $biensLesPlusVisites = Visite::whereBetween('date_visite', [$dateDebut, $dateFin])
            ->select(
                'bien_id',
                DB::raw('count(*) as total_visites'),
                DB::raw('SUM(CASE WHEN statut = "realisee" THEN 1 ELSE 0 END) as visites_realisees')
            )
            ->with('bien:id,reference,titre,quartier,ville,prix,type_bien')
            ->groupBy('bien_id')
            ->orderByDesc('total_visites')
            ->limit(5)
            ->get()
            ->map(fn($row) => [
                'bien' => $row->bien ? [
                    'id'        => $row->bien->id,
                    'reference' => $row->bien->reference,
                    'titre'     => $row->bien->titre,
                    'quartier'  => $row->bien->quartier,
                    'ville'     => $row->bien->ville,
                    'prix'      => round($row->bien->prix),
                    'type_bien' => $row->bien->type_bien->value,
                ] : null,
                'total_visites'     => $row->total_visites,
                'visites_realisees' => $row->visites_realisees,
            ]);

        $visitesAVenir = Visite::where('statut', StatutVisite::PLANIFIEE->value)
            ->whereBetween('date_visite', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->with([
                'bien:id,reference,titre,quartier',
                'client:id,nom,prenom,telephone',
            ])
            ->orderBy('date_visite')
            ->orderBy('heure_visite')
            ->limit(10)
            ->get()
            ->map(fn($v) => [
                'id'           => $v->id,
                'date_visite'  => $v->date_visite->toDateString(),
                'heure_visite' => $v->heure_visite?->format('H:i'),
                'bien' => $v->bien ? [
                    'reference' => $v->bien->reference,
                    'titre'     => $v->bien->titre,
                    'quartier'  => $v->bien->quartier,
                ] : null,
                'client' => $v->client ? [
                    'nom_complet' => $v->client->prenom . ' ' . $v->client->nom,
                    'telephone'   => $v->client->telephone,
                ] : null,
            ]);

        $data = [
            'periode' => [
                'debut' => $dateDebut->toDateString(),
                'fin'   => $dateFin->toDateString(),
            ],
            'totaux' => [
                'total'             => $total,
                'planifiees'        => $totaux->planifiees ?? 0,
                'realisees'         => $realisees,
                'annulees'          => $totaux->annulees ?? 0,
                'taux_confirmation' => $tauxConfirmation,
                'taux_annulation'   => $tauxAnnulation,
                'note_moyenne'      => $totaux->note_moyenne ? round($totaux->note_moyenne, 1) : null,
            ],
            'evolution_quotidienne'  => $evolutionQuotidienne,
            'par_jour_semaine'       => $parJourSemaine,
            'biens_les_plus_visites' => $biensLesPlusVisites,
            'visites_a_venir_7j'     => $visitesAVenir,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des visites',
            'data'    => $data,
        ]);
    }

    /**
     * Flux d'activité récente
     * GET /api/v1/dashboard/activite
     */
    public function activite(): JsonResponse
    {
        $dernieresTransactions = Transaction::with([
                'bien:id,reference,titre,quartier',
                'client:id,nom,prenom',
                'agent:id,name',
            ])
            ->latest('date_signature')
            ->limit(5)
            ->get()
            ->map(fn($t) => [
                'id'         => $t->id,
                'reference'  => $t->reference,
                'type'       => $t->type->value,
                'type_label' => $t->type->label(),
                'montant'    => round($t->montant),
                'date'       => $t->date_signature?->toDateString(),
                'bien' => $t->bien ? [
                    'reference' => $t->bien->reference,
                    'titre'     => $t->bien->titre,
                    'quartier'  => $t->bien->quartier,
                ] : null,
                'client' => $t->client ? [
                    'nom_complet' => $t->client->prenom . ' ' . $t->client->nom,
                ] : null,
                'agent' => $t->agent ? [
                    'name' => $t->agent->name,
                ] : null,
            ]);

        $dernieresVisites = Visite::with([
                'bien:id,reference,titre,quartier',
                'client:id,nom,prenom',
            ])
            ->latest('date_visite')
            ->limit(5)
            ->get()
            ->map(fn($v) => [
                'id'           => $v->id,
                'date_visite'  => $v->date_visite->toDateString(),
                'statut'       => $v->statut->value,
                'statut_label' => $v->statut->label(),
                'note_client'  => $v->note_client,
                'bien' => $v->bien ? [
                    'reference' => $v->bien->reference,
                    'titre'     => $v->bien->titre,
                    'quartier'  => $v->bien->quartier,
                ] : null,
                'client' => $v->client ? [
                    'nom_complet' => $v->client->prenom . ' ' . $v->client->nom,
                ] : null,
            ]);

        $derniersBiens = Bien::with('agent:id,name')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($b) => [
                'id'              => $b->id,
                'reference'       => $b->reference,
                'titre'           => $b->titre,
                'type_bien'       => $b->type_bien->value,
                'type_bien_label' => $b->type_bien->label(),
                'prix'            => round($b->prix),
                'quartier'        => $b->quartier,
                'ville'           => $b->ville,
                'statut'          => $b->statut->value,
                'statut_label'    => $b->statut->label(),
                'created_at'      => $b->created_at->toDateTimeString(),
                'agent' => $b->agent ? [
                    'name' => $b->agent->name,
                ] : null,
            ]);

        $derniersClients = Client::with('agent:id,name')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'id'          => $c->id,
                'nom_complet' => $c->prenom . ' ' . $c->nom,
                'email'       => $c->email,
                'telephone'   => $c->telephone,
                'is_active'   => $c->is_active,
                'created_at'  => $c->created_at->toDateTimeString(),
                'agent' => $c->agent ? [
                    'name' => $c->agent->name,
                ] : null,
            ]);

        $data = [
            'dernieres_transactions' => $dernieresTransactions,
            'dernieres_visites'      => $dernieresVisites,
            'derniers_biens'         => $derniersBiens,
            'derniers_clients'       => $derniersClients,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Activite recente de la plateforme',
            'data'    => $data,
        ]);
    }
}