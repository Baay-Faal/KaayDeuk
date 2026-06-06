<x-app-layout>
    <x-slot name="title">Clients</x-slot>
    <x-slot name="subtitle">{{ $clients->total() }} client(s)</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Clients</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="kd-card mb-6">
        <form method="GET" action="{{ route('web.clients.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="kd-label">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom, prénom, email, téléphone..." class="kd-input">
                </div>
                <div>
                    <label class="kd-label">Statut</label>
                    <select name="is_active" class="kd-input">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="kd-btn kd-btn-primary">Rechercher</button>
                <a href="{{ route('web.clients.index') }}" class="kd-btn kd-btn-outline">Réinitialiser</a>
            </div>
        </form>
    </div>

    {{-- Liste --}}
    @if($clients->isEmpty())
    <div class="kd-card"><div class="kd-empty"><p class="kd-empty-title">Aucun client trouvé</p></div></div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
        @foreach($clients as $client)
        <div class="kd-card hover:-translate-y-1 transition-transform duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="kd-avatar">
                        {{ strtoupper(substr($client->prenom, 0, 1)) }}{{ strtoupper(substr($client->nom, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm" style="color: var(--kd-black)">{{ $client->prenom }} {{ $client->nom }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $client->email }}</p>
                    </div>
                </div>
                <span class="kd-badge {{ $client->is_active ? 'kd-badge-green' : 'kd-badge-gray' }}">
                    {{ $client->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>

            <div class="space-y-2 mb-4 text-sm">
                @if($client->telephone)
                <div class="flex items-center gap-2" style="color: var(--kd-gray-600)">
                    <svg class="w-3.5 h-3.5 text-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $client->telephone }}
                </div>
                @endif
                @if($client->budget_min || $client->budget_max)
                <div class="flex items-center gap-2" style="color: var(--kd-gray-600)">
                    <svg class="w-3.5 h-3.5 text-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs">{{ $client->budget_format }}</span>
                </div>
                @endif
            </div>

            <a href="{{ route('web.clients.show', $client->id) }}" class="kd-btn kd-btn-dark w-full justify-center text-sm">
                Voir le profil
            </a>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($clients->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-sm" style="color: var(--kd-gray-400)">{{ $clients->firstItem() }}–{{ $clients->lastItem() }} sur {{ $clients->total() }}</p>
        <div class="kd-pagination">
            @if($clients->onFirstPage())
                <span class="kd-page-btn opacity-40">←</span>
            @else
                <a href="{{ $clients->previousPageUrl() }}" class="kd-page-btn">←</a>
            @endif
            @foreach($clients->getUrlRange(1, $clients->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="kd-page-btn {{ $page === $clients->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($clients->hasMorePages())
                <a href="{{ $clients->nextPageUrl() }}" class="kd-page-btn">→</a>
            @else
                <span class="kd-page-btn opacity-40">→</span>
            @endif
        </div>
    </div>
    @endif
    @endif
</x-app-layout>