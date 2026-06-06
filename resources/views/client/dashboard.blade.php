<x-app-layout>
    <x-slot name="title">Mon Espace</x-slot>
    <x-slot name="subtitle">Bienvenue, {{ Auth::user()->prenom ?? Auth::user()->name }}</x-slot>

    {{-- Message succès --}}
    @if(session('success'))
    <div class="kd-alert-auto mb-6 p-4 rounded-xl flex items-center gap-3"
         style="background: #DCFCE7; border: 1px solid #86EFAC; color: #15803D">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="kd-kpi">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="kd-kpi-value">{{ $stats['visites_total'] }}</div>
            <div class="kd-kpi-label">Visites au total</div>
            <span class="kd-badge kd-badge-orange">{{ $stats['visites_planifiees'] }} planifiées</span>
        </div>

        <div class="kd-kpi">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="kd-kpi-value">{{ $stats['favoris_total'] }}</div>
            <div class="kd-kpi-label">Biens en favoris</div>
        </div>

        <div class="kd-kpi">
            <div class="kd-kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="kd-kpi-value">{{ \App\Models\Bien::where('statut', 'disponible')->count() }}</div>
            <div class="kd-kpi-label">Biens disponibles</div>
            <a href="{{ route('web.biens.index') }}" class="text-xs font-semibold text-gold">Voir tous →</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Mes dernières visites --}}
        <div class="kd-card">
            <div class="flex items-center justify-between mb-4">
                <div class="kd-card-title mb-0">Mes visites récentes</div>
                <a href="{{ route('client.mes-visites') }}" class="text-xs font-semibold text-gold">Voir tout →</a>
            </div>

            @forelse($mesVisites as $visite)
            <div class="kd-stat-mini">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate" style="color: var(--kd-black)">
                        {{ $visite->bien->titre ?? 'Bien' }}
                    </p>
                    <p class="text-xs" style="color: var(--kd-gray-400)">
                        {{ $visite->date_visite->format('d/m/Y') }}
                        @if($visite->heure_visite) à {{ $visite->heure_visite->format('H:i') }} @endif
                    </p>
                </div>
                <span class="kd-badge {{ match($visite->statut->value) {
                    'realisee'  => 'kd-badge-green',
                    'annulee'   => 'kd-badge-red',
                    default     => 'kd-badge-orange',
                } }} flex-shrink-0">{{ $visite->statut->label() }}</span>
            </div>
            @empty
            <div class="kd-empty py-8">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 mb-3" style="color: var(--kd-gray-200)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="kd-empty-title">Aucune visite planifiée</p>
                <p class="kd-empty-text mb-4">Trouvez un bien et demandez une visite</p>
                <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-primary">Voir les biens</a>
            </div>
            @endforelse
        </div>

        {{-- Mes favoris --}}
        <div class="kd-card">
            <div class="flex items-center justify-between mb-4">
                <div class="kd-card-title mb-0">Mes favoris</div>
                <a href="{{ route('client.mes-favoris') }}" class="text-xs font-semibold text-gold">Voir tout →</a>
            </div>

            @forelse($mesFavoris as $bien)
            <a href="{{ route('web.biens.show', $bien->id) }}" class="kd-stat-mini hover:no-underline group">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: var(--kd-gray-100)">
                    <svg class="w-5 h-5" style="color: var(--kd-gray-400)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate group-hover:text-gold transition-colors" style="color: var(--kd-black)">
                        {{ $bien->titre }}
                    </p>
                    <p class="text-xs" style="color: var(--kd-gray-400)">{{ $bien->quartier }}, {{ $bien->ville }}</p>
                </div>
                <p class="text-sm font-bold text-gold flex-shrink-0">
                    {{ number_format($bien->prix, 0, ',', ' ') }} FCFA
                </p>
            </a>
            @empty
            <div class="kd-empty py-8">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 mb-3" style="color: var(--kd-gray-200)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <p class="kd-empty-title">Aucun favori</p>
                <p class="kd-empty-text mb-4">Ajoutez des biens à vos favoris</p>
                <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-primary">Parcourir les biens</a>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>