<x-app-layout>
    <x-slot name="title">{{ $client->prenom }} {{ $client->nom }}</x-slot>
    <x-slot name="subtitle">Profil client</x-slot>

    <div class="mb-6">
        <a href="{{ route('web.clients.index') }}" class="kd-btn kd-btn-outline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sidebar profil --}}
        <div class="space-y-5">
            <div class="kd-card text-center">
                <div class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4" style="background: var(--kd-gold); color: var(--kd-black)">
                    {{ strtoupper(substr($client->prenom, 0, 1)) }}{{ strtoupper(substr($client->nom, 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold mb-1" style="color: var(--kd-black)">{{ $client->prenom }} {{ $client->nom }}</h2>
                <span class="kd-badge {{ $client->is_active ? 'kd-badge-green' : 'kd-badge-gray' }}">
                    {{ $client->is_active ? 'Actif' : 'Inactif' }}
                </span>

                <div class="mt-4 space-y-2 text-sm text-left">
                    @if($client->email)
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Email</span>
                        <span class="font-medium text-xs">{{ $client->email }}</span>
                    </div>
                    @endif
                    @if($client->telephone)
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Téléphone</span>
                        <span class="font-medium">{{ $client->telephone }}</span>
                    </div>
                    @endif
                    @if($client->adresse)
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Adresse</span>
                        <span class="font-medium text-xs">{{ $client->adresse }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Budget --}}
            <div class="kd-card">
                <div class="kd-card-title">Budget</div>
                <p class="text-lg font-bold text-gold">{{ $client->budget_format }}</p>
            </div>

            {{-- Agent --}}
            @if($client->agent)
            <div class="kd-card">
                <div class="kd-card-title">Agent responsable</div>
                <div class="flex items-center gap-3">
                    <div class="kd-avatar">{{ strtoupper(substr($client->agent->name, 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">{{ $client->agent->name }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $client->agent->email }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Contenu principal --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Statistiques --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="kd-kpi">
                    <div class="kd-kpi-value">{{ $client->visites->count() }}</div>
                    <div class="kd-kpi-label">Visites</div>
                </div>
                <div class="kd-kpi">
                    <div class="kd-kpi-value">{{ $client->transactions->count() }}</div>
                    <div class="kd-kpi-label">Transactions</div>
                </div>
                <div class="kd-kpi">
                    <div class="kd-kpi-value">{{ $client->biensFavoris->count() }}</div>
                    <div class="kd-kpi-label">Favoris</div>
                </div>
            </div>

            {{-- Visites --}}
            @if($client->visites->count() > 0)
            <div class="kd-card">
                <div class="kd-card-title">Visites récentes</div>
                <table class="kd-table">
                    <thead><tr><th>Bien</th><th>Date</th><th>Statut</th><th>Note</th></tr></thead>
                    <tbody>
                        @foreach($client->visites->take(5) as $visite)
                        <tr>
                            <td class="text-sm">{{ Str::limit($visite->bien->titre ?? '—', 35) }}</td>
                            <td class="text-sm">{{ $visite->date_visite->format('d/m/Y') }}</td>
                            <td>
                                <span class="kd-badge {{ match($visite->statut->value) {
                                    'realisee' => 'kd-badge-green',
                                    'annulee'  => 'kd-badge-red',
                                    default    => 'kd-badge-orange',
                                } }}">{{ $visite->statut->label() }}</span>
                            </td>
                            <td class="font-semibold text-gold">{{ $visite->note_client ? $visite->note_client.'/5' : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Transactions --}}
            @if($client->transactions->count() > 0)
            <div class="kd-card">
                <div class="kd-card-title">Transactions</div>
                <table class="kd-table">
                    <thead><tr><th>Référence</th><th>Bien</th><th>Type</th><th>Montant</th><th></th></tr></thead>
                    <tbody>
                        @foreach($client->transactions->take(5) as $transaction)
                        <tr>
                            <td><span class="font-mono text-xs text-gold">{{ $transaction->reference }}</span></td>
                            <td class="text-sm">{{ Str::limit($transaction->bien->titre ?? '—', 30) }}</td>
                            <td>
                                <span class="kd-badge {{ $transaction->type->value === 'vente' ? 'kd-badge-blue' : 'kd-badge-purple' }}">
                                    {{ $transaction->type->label() }}
                                </span>
                            </td>
                            <td class="font-semibold text-sm">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <a href="{{ route('web.transactions.show', $transaction->id) }}" class="kd-btn kd-btn-outline py-1 px-2 text-xs">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Notes --}}
            @if($client->notes)
            <div class="kd-card">
                <div class="kd-card-title">Notes</div>
                <p class="text-sm leading-relaxed" style="color: var(--kd-gray-600)">{{ $client->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>