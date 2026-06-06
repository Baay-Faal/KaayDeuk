<x-app-layout>
    <x-slot name="title">Mes Favoris</x-slot>
    <x-slot name="subtitle">Vos biens sauvegardés</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Mes Favoris</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
        <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-outline">
            Parcourir les biens →
        </a>
    </div>

    @if($favoris instanceof \Illuminate\Pagination\LengthAwarePaginator && $favoris->isEmpty() || $favoris instanceof \Illuminate\Support\Collection && $favoris->isEmpty())
    <div class="kd-card">
        <div class="kd-empty">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="kd-empty-title">Aucun favori</p>
            <p class="kd-empty-text mb-4">Ajoutez des biens à vos favoris en les visitant</p>
            <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-primary">Voir les biens</a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($favoris as $bien)
        <div class="kd-card p-0 overflow-hidden group hover:-translate-y-1 transition-transform duration-200">
            <div class="relative h-44 flex items-center justify-center"
                 style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0)">
                <span class="text-xs font-mono" style="color: #bbb">[PROPERTY_IMAGE_PLACEHOLDER]</span>
                <div class="absolute top-3 left-3">
                    <span class="kd-badge {{ $bien->type_transaction->value === 'vente' ? 'kd-badge-blue' : 'kd-badge-purple' }}">
                        {{ $bien->type_transaction->label() }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <p class="text-lg font-bold mb-1" style="color: var(--kd-gold)">
                    {{ number_format($bien->prix, 0, ',', ' ') }} FCFA
                </p>
                <h3 class="text-sm font-semibold mb-2 line-clamp-2" style="color: var(--kd-black)">{{ $bien->titre }}</h3>
                <p class="text-xs mb-3" style="color: var(--kd-gray-400)">{{ $bien->quartier }}, {{ $bien->ville }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('web.biens.show', $bien->id) }}" class="kd-btn kd-btn-dark flex-1 justify-center text-sm">
                        Voir le bien
                    </a>
                    @if($bien->statut->value === 'disponible')
                    <a href="{{ route('client.demande-visite', $bien->id) }}" class="kd-btn kd-btn-primary px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</x-app-layout>