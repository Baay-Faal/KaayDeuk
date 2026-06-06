<x-app-layout>
    <x-slot name="title">Biens immobiliers</x-slot>
    <x-slot name="subtitle">{{ $biens->total() }} bien(s) au total</x-slot>

    {{-- ── En-tête page ── --}}
    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Parc immobilier</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
    </div>

    {{-- ── Filtres ── --}}
    <div class="kd-card mb-6" x-data="filtres()">
        <form method="GET" action="{{ route('web.biens.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                <div>
                    <label class="kd-label">Type de bien</label>
                    <select name="type_bien" class="kd-input">
                        <option value="">Tous les types</option>
                        @foreach($typesBien as $type)
                            <option value="{{ $type->value }}" {{ request('type_bien') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="kd-label">Transaction</label>
                    <select name="type_transaction" class="kd-input">
                        <option value="">Vente & Location</option>
                        @foreach($typesTransaction as $type)
                            <option value="{{ $type->value }}" {{ request('type_transaction') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="kd-label">Statut</label>
                    <select name="statut" class="kd-input">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut->value }}" {{ request('statut') === $statut->value ? 'selected' : '' }}>
                                {{ $statut->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="kd-label">Ville</label>
                    <input type="text" name="ville" value="{{ request('ville') }}"
                           placeholder="Ex: Dakar, Thiès..." class="kd-input">
                </div>

                <div>
                    <label class="kd-label">Prix min (FCFA)</label>
                    <input type="number" name="prix_min" value="{{ request('prix_min') }}"
                           placeholder="0" class="kd-input">
                </div>

                <div>
                    <label class="kd-label">Prix max (FCFA)</label>
                    <input type="number" name="prix_max" value="{{ request('prix_max') }}"
                           placeholder="999 999 999" class="kd-input">
                </div>

            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="kd-btn kd-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-outline">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- ── Grille de biens ── --}}
    @if($biens->isEmpty())
        <div class="kd-card">
            <div class="kd-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
                <p class="kd-empty-title">Aucun bien trouvé</p>
                <p class="kd-empty-text">Modifiez vos filtres pour voir plus de résultats.</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
            @foreach($biens as $bien)
            <div class="kd-card p-0 overflow-hidden group hover:-translate-y-1 transition-transform duration-200">

                {{-- Image placeholder --}}
                <div class="relative h-48 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0)">
                    <span class="text-xs font-mono" style="color: #bbb">[PROPERTY_IMAGE_PLACEHOLDER]</span>

                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="kd-badge {{ $bien->type_transaction->value === 'vente' ? 'kd-badge-blue' : 'kd-badge-purple' }}">
                            {{ $bien->type_transaction->label() }}
                        </span>
                        <span class="kd-badge {{ match($bien->statut->value) {
                            'disponible' => 'kd-badge-green',
                            'reserve'    => 'kd-badge-orange',
                            'vendu'      => 'kd-badge-blue',
                            'loue'       => 'kd-badge-purple',
                            default      => 'kd-badge-gray',
                        } }}">
                            {{ $bien->statut->label() }}
                        </span>
                    </div>

                    {{-- Vues --}}
                    <div class="absolute top-3 right-3 flex items-center gap-1 bg-black/50 rounded-lg px-2 py-1">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-white text-xs">{{ $bien->nombre_vues }}</span>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <p class="text-lg font-bold" style="color: var(--kd-gold)">
                            {{ number_format($bien->prix, 0, ',', ' ') }} FCFA
                        </p>
                        <span class="kd-badge kd-badge-gray text-xs">{{ $bien->type_bien->label() }}</span>
                    </div>

                    <h3 class="text-sm font-semibold mb-2 line-clamp-2" style="color: var(--kd-black)">
                        {{ $bien->titre }}
                    </h3>

                    <div class="flex items-center gap-1 mb-3" style="color: var(--kd-gray-400)">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span class="text-xs">{{ $bien->quartier }}, {{ $bien->ville }}</span>
                    </div>

                    <div class="flex items-center gap-4 mb-4 text-xs" style="color: var(--kd-gray-600)">
                        @if($bien->surface)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            {{ number_format($bien->surface, 0) }} m²
                        </div>
                        @endif
                        @if($bien->nombre_chambres)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            {{ $bien->nombre_chambres }} ch.
                        </div>
                        @endif
                        @if($bien->nombre_pieces)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                            </svg>
                            {{ $bien->nombre_pieces }} pièces
                        </div>
                        @endif
                    </div>

                    {{-- Caractéristiques --}}
                    <div class="flex items-center gap-2 mb-4">
                        @if($bien->meuble)
                            <span class="kd-badge kd-badge-gold">Meublé</span>
                        @endif
                        @if($bien->climatise)
                            <span class="kd-badge kd-badge-blue">Climatisé</span>
                        @endif
                        @if($bien->securise)
                            <span class="kd-badge kd-badge-green">Sécurisé</span>
                        @endif
                    </div>

                    <a href="{{ route('web.biens.show', $bien->id) }}"
                       class="kd-btn kd-btn-dark w-full justify-center">
                        Voir le détail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between">
            <p class="text-sm" style="color: var(--kd-gray-400)">
                {{ $biens->firstItem() }}–{{ $biens->lastItem() }} sur {{ $biens->total() }} biens
            </p>
            <div class="kd-pagination">
                @if($biens->onFirstPage())
                    <span class="kd-page-btn opacity-40">←</span>
                @else
                    <a href="{{ $biens->previousPageUrl() }}" class="kd-page-btn">←</a>
                @endif

                @foreach($biens->getUrlRange(1, $biens->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="kd-page-btn {{ $page === $biens->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($biens->hasMorePages())
                    <a href="{{ $biens->nextPageUrl() }}" class="kd-page-btn">→</a>
                @else
                    <span class="kd-page-btn opacity-40">→</span>
                @endif
            </div>
        </div>
    @endif

</x-app-layout>