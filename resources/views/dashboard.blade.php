<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Bienvenue, {{ Auth::user()->prenom ?? Auth::user()->name }} 👋</x-slot>

    {{-- ── KPIs ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Biens --}}
        <div class="kd-kpi kd-animate">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div>
                <div class="kd-kpi-value">{{ $biens['total'] }}</div>
                <div class="kd-kpi-label">Biens au total</div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="kd-badge kd-badge-green">{{ $biens['disponibles'] }} dispo</span>
                <span class="kd-badge kd-badge-orange">{{ $biens['reserves'] }} réservés</span>
                <span class="kd-badge kd-badge-blue">{{ $biens['vendus'] }} vendus</span>
            </div>
        </div>

        {{-- Clients --}}
        <div class="kd-kpi kd-animate">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="kd-kpi-value">{{ $clients['total'] }}</div>
                <div class="kd-kpi-label">Clients enregistrés</div>
            </div>
            <div class="flex items-center gap-3">
                <span class="kd-badge kd-badge-green">{{ $clients['actifs'] }} actifs</span>
                <span class="kd-badge kd-badge-gray">{{ $clients['total'] - $clients['actifs'] }} inactifs</span>
            </div>
        </div>

        {{-- Transactions --}}
        <div class="kd-kpi kd-animate">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div class="kd-kpi-value">{{ $transactions['total'] }}</div>
                <div class="kd-kpi-label">Transactions</div>
            </div>
            <div>
                <span class="kd-badge kd-badge-gold">
                    {{ number_format($transactions['chiffre_affaires'], 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>

        {{-- Visites --}}
        <div class="kd-kpi kd-animate">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="kd-kpi-value">{{ $visites['total'] }}</div>
                <div class="kd-kpi-label">Visites planifiées</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="kd-kpi-trend up">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $visites['taux_confirmation'] }}% confirmation
                </div>
            </div>
        </div>
    </div>

    {{-- ── Ligne 2 : Graphique + Biens populaires ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

        {{-- Évolution CA --}}
        <div class="kd-card lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="kd-card-title mb-0">Chiffre d'affaires</div>
                    <p class="text-xs mt-1" style="color: var(--kd-gray-400)">6 derniers mois</p>
                </div>
                <div class="kd-badge kd-badge-gold">FCFA</div>
            </div>
            <canvas id="chartCA" height="200"></canvas>
        </div>

        {{-- Biens populaires --}}
        <div class="kd-card">
            <div class="kd-card-title">Biens les + vus</div>
            <div class="space-y-1">
                @forelse($biensPopulaires as $bien)
                <a href="{{ route('web.biens.show', $bien->id) }}" class="kd-stat-mini hover:no-underline group">
                    <div class="flex-1 min-w-0 pr-3">
                        <p class="text-sm font-semibold truncate group-hover:text-gold transition-colors" style="color: var(--kd-black)">
                            {{ $bien->titre }}
                        </p>
                        <p class="text-xs mt-0.5" style="color: var(--kd-gray-400)">{{ $bien->quartier }}, {{ $bien->ville }}</p>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-xs font-semibold" style="color: var(--kd-gray-600)">{{ $bien->nombre_vues }}</span>
                    </div>
                </a>
                @empty
                <div class="kd-empty">
                    <p class="kd-empty-text">Aucun bien</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Ligne 3 : Répartition biens + Dernières visites ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

        {{-- Répartition statuts --}}
        <div class="kd-card">
            <div class="kd-card-title">Répartition des biens</div>
            <div class="flex items-center gap-6">
                <canvas id="chartBiens" width="160" height="160" style="max-width:160px"></canvas>
                <div class="flex-1 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:#22C55E"></div>
                            <span class="text-sm" style="color: var(--kd-gray-600)">Disponibles</span>
                        </div>
                        <span class="text-sm font-bold" style="color: var(--kd-black)">{{ $biens['disponibles'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:#F59E0B"></div>
                            <span class="text-sm" style="color: var(--kd-gray-600)">Réservés</span>
                        </div>
                        <span class="text-sm font-bold" style="color: var(--kd-black)">{{ $biens['reserves'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:#3B82F6"></div>
                            <span class="text-sm" style="color: var(--kd-gray-600)">Vendus</span>
                        </div>
                        <span class="text-sm font-bold" style="color: var(--kd-black)">{{ $biens['vendus'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:#8B5CF6"></div>
                            <span class="text-sm" style="color: var(--kd-gray-600)">Loués</span>
                        </div>
                        <span class="text-sm font-bold" style="color: var(--kd-black)">{{ $biens['loues'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dernières visites --}}
        <div class="kd-card">
            <div class="flex items-center justify-between mb-4">
                <div class="kd-card-title mb-0">Dernières visites</div>
                <a href="{{ route('web.visites.index') }}" class="text-xs font-semibold text-gold">Voir tout →</a>
            </div>
            <div class="space-y-1">
                @forelse($dernieresVisites as $visite)
                <div class="kd-stat-mini">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="kd-avatar text-xs flex-shrink-0" style="width:32px;height:32px;font-size:10px">
                            {{ strtoupper(substr($visite->client->prenom ?? 'C', 0, 1)) }}{{ strtoupper(substr($visite->client->nom ?? '', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate" style="color: var(--kd-black)">
                                {{ $visite->client->prenom ?? '' }} {{ $visite->client->nom ?? 'Client' }}
                            </p>
                            <p class="text-xs truncate" style="color: var(--kd-gray-400)">
                                {{ $visite->bien->titre ?? 'Bien' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0 ml-2">
                        @php
                            $statutClass = match($visite->statut->value) {
                                'realisee'  => 'kd-badge-green',
                                'annulee'   => 'kd-badge-red',
                                default     => 'kd-badge-orange',
                            };
                        @endphp
                        <span class="kd-badge {{ $statutClass }}">{{ $visite->statut->label() }}</span>
                        <span class="text-xs" style="color: var(--kd-gray-400)">
                            {{ $visite->date_visite->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="kd-empty py-6">
                    <p class="kd-empty-text">Aucune visite</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Ligne 4 : Dernières transactions ── --}}
    <div class="kd-card">
        <div class="flex items-center justify-between mb-6">
            <div class="kd-card-title mb-0">Dernières transactions</div>
            <a href="{{ route('web.transactions.index') }}" class="text-xs font-semibold text-gold">Voir tout →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="kd-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Bien</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresTransactions as $transaction)
                    <tr>
                        <td>
                            <span class="font-mono text-xs font-semibold" style="color: var(--kd-gold)">
                                {{ $transaction->reference }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-medium truncate" style="max-width:180px; display:block">
                                {{ $transaction->bien->titre ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="kd-avatar" style="width:28px;height:28px;font-size:10px">
                                    {{ strtoupper(substr($transaction->client->prenom ?? 'C', 0, 1)) }}
                                </div>
                                <span class="text-sm">{{ $transaction->client->prenom ?? '' }} {{ $transaction->client->nom ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="kd-badge {{ $transaction->type->value === 'vente' ? 'kd-badge-blue' : 'kd-badge-purple' }}">
                                {{ $transaction->type->label() }}
                            </span>
                        </td>
                        <td>
                            <span class="font-semibold text-sm">
                                {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                            </span>
                        </td>
                        <td>
                            <span class="text-sm" style="color: var(--kd-gray-400)">
                                {{ $transaction->date_signature ? $transaction->date_signature->format('d/m/Y') : '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('web.transactions.show', $transaction->id) }}"
                                   class="kd-btn kd-btn-outline py-1 px-3 text-xs">
                                    Voir
                                </a>
                                <a href="{{ route('pdf.recu', $transaction->id) }}"
                                   target="_blank"
                                   class="kd-btn kd-btn-primary py-1 px-3 text-xs">
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8" style="color: var(--kd-gray-400)">
                            Aucune transaction
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Scripts Chart.js ── --}}
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        // Données évolution mensuelle
        const evolutionData = @json($evolutionMensuelle);
        const moisLabels = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

        const labels = evolutionData.map(d => moisLabels[d.mois - 1] + ' ' + d.annee);
        const totaux = evolutionData.map(d => parseFloat(d.total) || 0);

        // Graphique CA
        const ctxCA = document.getElementById('chartCA').getContext('2d');
        new Chart(ctxCA, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['Aucune donnée'],
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: totaux.length ? totaux : [0],
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212,175,55,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#D4AF37',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => new Intl.NumberFormat('fr-SN').format(ctx.raw) + ' FCFA'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: val => new Intl.NumberFormat('fr-SN', { notation: 'compact' }).format(val)
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Graphique Donut Biens
        const ctxBiens = document.getElementById('chartBiens').getContext('2d');
        new Chart(ctxBiens, {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Réservés', 'Vendus', 'Loués'],
                datasets: [{
                    data: [
                        {{ $biens['disponibles'] }},
                        {{ $biens['reserves'] }},
                        {{ $biens['vendus'] }},
                        {{ $biens['loues'] }}
                    ],
                    backgroundColor: ['#22C55E','#F59E0B','#3B82F6','#8B5CF6'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: false,
                cutout: '72%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
    @endpush

</x-app-layout>