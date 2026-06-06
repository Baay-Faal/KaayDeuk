<x-app-layout>
    <x-slot name="title">Transaction {{ $transaction->reference }}</x-slot>
    <x-slot name="subtitle">Détail de la transaction</x-slot>

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('web.transactions.index') }}" class="kd-btn kd-btn-outline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <a href="{{ route('pdf.contrat', $transaction->id) }}" target="_blank" class="kd-btn kd-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Contrat PDF
        </a>
        <a href="{{ route('pdf.recu', $transaction->id) }}" target="_blank" class="kd-btn kd-btn-dark">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reçu PDF
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            {{-- Montant principal --}}
            <div class="kd-card" style="background: var(--kd-black)">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-widest mb-1" style="color: var(--kd-gray-400)">
                            {{ $transaction->type->label() }}
                        </p>
                        <p class="text-3xl font-bold" style="color: var(--kd-gold)">
                            {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <span class="kd-badge kd-badge-gold text-sm px-4 py-1">
                        {{ $transaction->reference }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6" style="border-top: 1px solid rgba(255,255,255,0.08)">
                    <div>
                        <p class="text-xs" style="color: var(--kd-gray-400)">Commission agence</p>
                        <p class="font-bold text-white">{{ number_format($transaction->commission_agence, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--kd-gray-400)">Commission agent</p>
                        <p class="font-bold text-white">{{ number_format($transaction->commission_agent, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>

            {{-- Bien --}}
            @if($transaction->bien)
            <div class="kd-card">
                <div class="kd-card-title">Bien concerné</div>
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: var(--kd-gray-100)">
                        <span class="text-xs font-mono" style="color: #bbb">IMG</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold mb-1" style="color: var(--kd-black)">{{ $transaction->bien->titre }}</p>
                        <p class="text-xs mb-2" style="color: var(--kd-gray-400)">{{ $transaction->bien->quartier }}, {{ $transaction->bien->ville }}</p>
                        <div class="flex items-center gap-2">
                            <span class="kd-badge kd-badge-gray">{{ $transaction->bien->type_bien->label() }}</span>
                            <span class="kd-badge kd-badge-gold font-mono text-xs">{{ $transaction->bien->reference }}</span>
                        </div>
                    </div>
                    <a href="{{ route('web.biens.show', $transaction->bien->id) }}" class="kd-btn kd-btn-outline py-1 px-3 text-xs">
                        Voir
                    </a>
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($transaction->notes)
            <div class="kd-card">
                <div class="kd-card-title">Notes</div>
                <p class="text-sm leading-relaxed" style="color: var(--kd-gray-600)">{{ $transaction->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Dates --}}
            <div class="kd-card">
                <div class="kd-card-title">Dates</div>
                <div class="space-y-3 text-sm">
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Signature</span>
                        <span class="font-semibold">{{ $transaction->date_signature?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Début contrat</span>
                        <span class="font-semibold">{{ $transaction->date_debut_contrat?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Fin contrat</span>
                        <span class="font-semibold">{{ $transaction->date_fin_contrat?->format('d/m/Y') ?? 'Indéterminée' }}</span>
                    </div>
                </div>
            </div>

            {{-- Client --}}
            @if($transaction->client)
            <div class="kd-card">
                <div class="kd-card-title">Client</div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="kd-avatar">{{ strtoupper(substr($transaction->client->prenom ?? 'C', 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">
                            {{ $transaction->client->prenom }} {{ $transaction->client->nom }}
                        </p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $transaction->client->email }}</p>
                    </div>
                </div>
                <a href="{{ route('web.clients.show', $transaction->client->id) }}" class="kd-btn kd-btn-outline w-full justify-center text-xs py-2">
                    Voir le profil client
                </a>
            </div>
            @endif

            {{-- Agent --}}
            @if($transaction->agent)
            <div class="kd-card">
                <div class="kd-card-title">Agent</div>
                <div class="flex items-center gap-3">
                    <div class="kd-avatar">{{ strtoupper(substr($transaction->agent->name, 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">{{ $transaction->agent->name }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $transaction->agent->email }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>