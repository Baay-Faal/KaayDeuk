<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Visite;
use App\Models\Client;
use App\Enums\StatutVisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EspaceClientController extends Controller
{
    /**
     * Tableau de bord client
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Chercher le profil client lié à cet utilisateur
        $client = Client::where('email', $user->email)->first();

        $mesVisites = $client
            ? Visite::with(['bien', 'agent'])
                ->where('client_id', $client->id)
                ->orderBy('date_visite', 'desc')
                ->limit(5)
                ->get()
            : collect();

        $mesFavoris = $client
            ? $client->biensFavoris()->with('media')->limit(6)->get()
            : collect();

        $stats = [
            'visites_total'     => $client ? Visite::where('client_id', $client->id)->count() : 0,
            'visites_planifiees' => $client ? Visite::where('client_id', $client->id)->where('statut', StatutVisite::PLANIFIEE->value)->count() : 0,
            'favoris_total'     => $client ? $client->biensFavoris()->count() : 0,
        ];

        return view('client.dashboard', compact('client', 'mesVisites', 'mesFavoris', 'stats'));
    }

    /**
     * Mes visites
     */
    public function mesVisites(Request $request)
    {
        $user   = Auth::user();
        $client = Client::where('email', $user->email)->first();

        $query = $client
            ? Visite::with(['bien', 'agent'])->where('client_id', $client->id)
            : Visite::whereNull('id');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $visites = $query->orderBy('date_visite', 'desc')->paginate(10)->withQueryString();
        $statuts = StatutVisite::cases();

        return view('client.mes-visites', compact('visites', 'statuts', 'client'));
    }

    /**
     * Mes favoris
     */
    public function mesFavoris()
    {
        $user   = Auth::user();
        $client = Client::where('email', $user->email)->first();

        $favoris = $client
            ? $client->biensFavoris()->with('media')->paginate(12)
            : collect();

        return view('client.mes-favoris', compact('favoris', 'client'));
    }

    /**
     * Formulaire demande de visite
     */
    public function demandeVisite(Bien $bien)
    {
        return view('client.demande-visite', compact('bien'));
    }

    /**
     * Soumettre demande de visite
     */
    public function soumettreVisite(Request $request, Bien $bien)
    {
        $request->validate([
            'date_visite'  => ['required', 'date', 'after:today'],
            'heure_visite' => ['required', 'date_format:H:i'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ], [
            'date_visite.required'    => 'La date est obligatoire.',
            'date_visite.after'       => 'La date doit être dans le futur.',
            'heure_visite.required'   => 'L\'heure est obligatoire.',
            'heure_visite.date_format' => 'Format d\'heure invalide (HH:MM).',
        ]);

        $user   = Auth::user();
        $client = Client::where('email', $user->email)->first();

        // Créer automatiquement un profil client si inexistant
        if (!$client) {
            $client = Client::create([
                'nom'      => $user->name,
                'prenom'   => $user->prenom ?? $user->name,
                'email'    => $user->email,
                'telephone' => $user->telephone ?? '—',
                'agent_id' => $bien->agent_id,
                'is_active' => true,
            ]);
        }

        // Créer la visite
        $visite = Visite::create([
            'bien_id'      => $bien->id,
            'client_id'    => $client->id,
            'agent_id'     => $bien->agent_id,
            'date_visite'  => $request->date_visite,
            'heure_visite' => $request->date_visite . ' ' . $request->heure_visite . ':00',
            'statut'       => StatutVisite::PLANIFIEE->value,
            'notes'        => $request->notes,
        ]);

        // Notifier l'agent
        if ($bien->agent) {
            $bien->agent->notify(new \App\Notifications\VisitePlanifieeNotification($visite->load('bien', 'client')));
        }

        return redirect()->route('client.mes-visites')
            ->with('success', 'Votre demande de visite a été envoyée avec succès ! L\'agent vous contactera pour confirmer.');
    }
}