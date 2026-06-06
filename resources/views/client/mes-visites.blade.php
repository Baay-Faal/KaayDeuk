<x-app-layout>
    <x-slot name="title">Mes Visites</x-slot>
    <x-slot name="subtitle">Historique de vos demandes de visite</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Mes Visites</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
        <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Demander une visite
        </a>
    </div>

    {{-- Filtres --}}
    <div class="kd-card mb-6">
        <form method="GET" action="{{ route('client.mes-visites') }}">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <label class="kd-label">Statut</label>
                    <select name="statut" class="kd-input">
                        <option value="">Toutes</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut->value }}" {{ request('statut') === $statut->value ? 'selected' : '' }}>
                                {{ $statut->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-5">
                    <button type="submit" class="kd-btn kd-btn-primary">Filtrer</button>
                    <a href="{{ route('client.mes-visites') }}" class="kd-btn kd-btn-outline ml-2">Réinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="kd-alert-auto mb-6 p-4 rounded-xl flex items-center gap-3"
         style="background: #DCFCE7; border: 1px solid #86EFAC; color: #15803D">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-4">
        @forelse($visites as $visite)
        <div class="kd-card">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center flex-shrink-0"
                         style="background: rgba(212,175,55,0.1)">
                        <span class="text-lg font-bold" style="color: var(--kd-gold)">{{ $visite->date_visite->format('d') }}</span>
                        <span class="text-xs" style="color: var(--kd-gray-400)">{{ $visite->date_visite->format('M') }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm mb-1" style="color: var(--kd-black)">
                            {{ $visite->bien->titre ?? 'Bien immobilier' }}
                        </h3>
                        <p class="text-xs mb-2" style="color: var(--kd-gray-400)">
                            {{ $visite->bien->quartier ?? '' }}, {{ $visite->bien->ville ?? '' }}
                        </p>
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($visite->heure_visite)
                            <span class="text-xs flex items-center gap-1" style="color: var(--kd-gray-600)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $visite->heure_visite->format('H:i') }}
                            </span>
                            @endif
                            @if($visite->agent)
                            <span class="text-xs" style="color: var(--kd-gray-600)">
                                Agent : {{ $visite->agent->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="kd-badge {{ match($visite->statut->value) {
                        'realisee'  => 'kd-badge-green',
                        'annulee'   => 'kd-badge-red',
                        default     => 'kd-badge-orange',
                    } }} text-sm px-3 py-1">{{ $visite->statut->label() }}</span>
                    <a href="{{ route('web.biens.show', $visite->bien_id) }}" class="kd-btn kd-btn-outline py-1 px-3 text-xs">
                        Voir le bien
                    </a>
                </div>
            </div>

            @if($visite->notes)
            <div class="mt-4 pt-4" style="border-top: 1px solid var(--kd-gray-100)">
                <p class="text-xs font-semibold mb-1" style="color: var(--kd-gray-400)">Votre message :</p>
                <p class="text-sm italic" style="color: var(--kd-gray-600)">"{{ $visite->notes }}"</p>
            </div>
            @endif

            @if($visite->rapport && $visite->statut->value === 'realisee')
            <div class="mt-4 pt-4" style="border-top: 1px solid var(--kd-gray-100)">
                <p class="text-xs font-semibold mb-1" style="color: var(--kd-gray-400)">Rapport de l'agent :</p>
                <p class="text-sm" style="color: var(--kd-gray-600)">{{ $visite->rapport }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="kd-card">
            <div class="kd-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="kd-empty-title">Aucune visite</p>
                <p class="kd-empty-text mb-4">Trouvez un bien et demandez une visite</p>
                <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-primary">Parcourir les biens</a>
            </div>
        </div>
        @endforelse
    </div>

    @if($visites->hasPages())
    <div class="flex items-center justify-center mt-6">
        <div class="kd-pagination">
            @if($visites->onFirstPage())
                <span class="kd-page-btn opacity-40">←</span>
            @else
                <a href="{{ $visites->previousPageUrl() }}" class="kd-page-btn">←</a>
            @endif
            @foreach($visites->getUrlRange(1, $visites->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="kd-page-btn {{ $page === $visites->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($visites->hasMorePages())
                <a href="{{ $visites->nextPageUrl() }}" class="kd-page-btn">→</a>
            @else
                <span class="kd-page-btn opacity-40">→</span>
            @endif
        </div>
    </div>
    @endif
</x-app-layout>