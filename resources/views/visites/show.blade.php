<x-app-layout>
    <x-slot name="title">Visite #{{ $visite->id }}</x-slot>
    <x-slot name="subtitle">Détail de la visite</x-slot>

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('web.visites.index') }}" class="kd-btn kd-btn-outline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <a href="{{ route('pdf.rapport_visite', $visite->id) }}" target="_blank" class="kd-btn kd-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Rapport PDF
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- Infos visite --}}
            <div class="kd-card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="kd-card-title mb-0">Informations</h2>
                    <span class="kd-badge {{ match($visite->statut->value) {
                        'realisee'  => 'kd-badge-green',
                        'annulee'   => 'kd-badge-red',
                        default     => 'kd-badge-orange',
                    } }} text-sm px-4 py-1">{{ $visite->statut->label() }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="p-4 rounded-xl text-center" style="background: var(--kd-gray-50)">
                        <p class="text-2xl font-bold" style="color: var(--kd-black)">{{ $visite->date_visite->format('d/m/Y') }}</p>
                        <p class="text-xs mt-1" style="color: var(--kd-gray-400)">Date de la visite</p>
                    </div>
                    <div class="p-4 rounded-xl text-center" style="background: var(--kd-gray-50)">
                        <p class="text-2xl font-bold" style="color: var(--kd-black)">{{ $visite->heure_visite?->format('H:i') ?? '—' }}</p>
                        <p class="text-xs mt-1" style="color: var(--kd-gray-400)">Heure</p>
                    </div>
                </div>

                @if($visite->note_client)
                <div class="p-4 rounded-xl mb-4 text-center" style="background: var(--kd-black)">
                    <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--kd-gray-400)">Note du client</p>
                    <p class="text-4xl font-bold" style="color: var(--kd-gold)">{{ $visite->note_client }}<span class="text-lg" style="color: var(--kd-gray-400)">/5</span></p>
                    <p class="text-gold text-xl mt-1">
                        @for($i = 1; $i <= 5; $i++){{ $i <= $visite->note_client ? '★' : '☆' }}@endfor
                    </p>
                </div>
                @endif
            </div>

            @if($visite->rapport)
            <div class="kd-card">
                <div class="kd-card-title">Rapport de l'agent</div>
                <p class="text-sm leading-relaxed" style="color: var(--kd-gray-600)">{{ $visite->rapport }}</p>
            </div>
            @endif

            @if($visite->commentaire_client)
            <div class="kd-card" style="border-left: 3px solid var(--kd-gold)">
                <div class="kd-card-title">Commentaire du client</div>
                <p class="text-sm leading-relaxed italic" style="color: var(--kd-gray-600)">"{{ $visite->commentaire_client }}"</p>
            </div>
            @endif
        </div>

        <div class="space-y-5">
            {{-- Bien --}}
            @if($visite->bien)
            <div class="kd-card">
                <div class="kd-card-title">Bien visité</div>
                <p class="font-semibold text-sm mb-1" style="color: var(--kd-black)">{{ $visite->bien->titre }}</p>
                <p class="text-xs mb-3" style="color: var(--kd-gray-400)">{{ $visite->bien->quartier }}, {{ $visite->bien->ville }}</p>
                <p class="font-bold text-gold mb-3">{{ number_format($visite->bien->prix, 0, ',', ' ') }} FCFA</p>
                <a href="{{ route('web.biens.show', $visite->bien->id) }}" class="kd-btn kd-btn-outline w-full justify-center text-xs py-2">Voir le bien</a>
            </div>
            @endif

            {{-- Client --}}
            @if($visite->client)
            <div class="kd-card">
                <div class="kd-card-title">Client</div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="kd-avatar">{{ strtoupper(substr($visite->client->prenom ?? 'C', 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">{{ $visite->client->prenom }} {{ $visite->client->nom }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $visite->client->telephone }}</p>
                    </div>
                </div>
                <a href="{{ route('web.clients.show', $visite->client->id) }}" class="kd-btn kd-btn-outline w-full justify-center text-xs py-2">Voir le profil</a>
            </div>
            @endif

            {{-- Agent --}}
            @if($visite->agent)
            <div class="kd-card">
                <div class="kd-card-title">Agent</div>
                <div class="flex items-center gap-3">
                    <div class="kd-avatar">{{ strtoupper(substr($visite->agent->name, 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">{{ $visite->agent->name }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $visite->agent->email }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>