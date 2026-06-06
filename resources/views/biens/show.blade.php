<x-app-layout>
    <x-slot name="title">{{ $bien->titre }}</x-slot>
    <x-slot name="subtitle">Référence : {{ $bien->reference }}</x-slot>

    {{-- Retour --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('web.biens.index') }}" class="kd-btn kd-btn-outline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux biens
        </a>
        {{-- Bouton demande de visite pour les clients --}}
        @if(Auth::user()->isClient() && $bien->statut->value === 'disponible')
            <a href="{{ route('client.demande-visite', $bien->id) }}" class="kd-btn kd-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Demander une visite
            </a>
        @endif
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Image principale --}}
            <div class="kd-card p-0 overflow-hidden">
                <div class="h-72 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0)">
                    <span class="text-sm font-mono" style="color: #bbb">[PROPERTY_IMAGE_PLACEHOLDER]</span>
                </div>
            </div>

            {{-- Infos principales --}}
            <div class="kd-card">
                <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
                    <div>
                        <h1 class="text-xl font-bold mb-2" style="color: var(--kd-black)">{{ $bien->titre }}</h1>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="kd-badge {{ $bien->type_transaction->value === 'vente' ? 'kd-badge-blue' : 'kd-badge-purple' }}">
                                {{ $bien->type_transaction->label() }}
                            </span>
                            <span class="kd-badge kd-badge-gray">{{ $bien->type_bien->label() }}</span>
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
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold" style="color: var(--kd-gold)">
                            {{ number_format($bien->prix, 0, ',', ' ') }} FCFA
                        </p>
                        @if($bien->type_transaction->value === 'location')
                            <p class="text-xs" style="color: var(--kd-gray-400)">par mois</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-1 mb-4" style="color: var(--kd-gray-400)">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="text-sm">{{ $bien->adresse }}, {{ $bien->quartier }}, {{ $bien->ville }}</span>
                </div>

                <div class="kd-gold-line mb-4"></div>

                {{-- Caractéristiques --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    @if($bien->surface)
                    <div class="text-center p-3 rounded-xl" style="background: var(--kd-gray-50)">
                        <p class="text-lg font-bold" style="color: var(--kd-black)">{{ number_format($bien->surface, 0) }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">m² surface</p>
                    </div>
                    @endif
                    @if($bien->nombre_pieces)
                    <div class="text-center p-3 rounded-xl" style="background: var(--kd-gray-50)">
                        <p class="text-lg font-bold" style="color: var(--kd-black)">{{ $bien->nombre_pieces }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">pièces</p>
                    </div>
                    @endif
                    @if($bien->nombre_chambres)
                    <div class="text-center p-3 rounded-xl" style="background: var(--kd-gray-50)">
                        <p class="text-lg font-bold" style="color: var(--kd-black)">{{ $bien->nombre_chambres }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">chambres</p>
                    </div>
                    @endif
                    @if($bien->nombre_salles_bain)
                    <div class="text-center p-3 rounded-xl" style="background: var(--kd-gray-50)">
                        <p class="text-lg font-bold" style="color: var(--kd-black)">{{ $bien->nombre_salles_bain }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">salles de bain</p>
                    </div>
                    @endif
                </div>

                {{-- Options --}}
                <div class="flex items-center gap-2 flex-wrap mb-4">
                    @if($bien->meuble)    <span class="kd-badge kd-badge-gold">Meublé</span>    @endif
                    @if($bien->climatise) <span class="kd-badge kd-badge-blue">Climatisé</span> @endif
                    @if($bien->securise)  <span class="kd-badge kd-badge-green">Sécurisé</span> @endif
                </div>

                @if($bien->description)
                <div class="kd-gold-line mb-4"></div>
                <p class="text-sm leading-relaxed" style="color: var(--kd-gray-600)">{{ $bien->description }}</p>
                @endif
            </div>

            {{-- Formulaire demande de visite intégré (pour clients) --}}
            @if(Auth::user()->isClient() && $bien->statut->value === 'disponible')
            <div class="kd-card" style="border: 2px solid var(--kd-gold)">
                <div class="flex items-center gap-3 mb-6">
                    <div class="kd-kpi-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-base" style="color: var(--kd-black)">Demander une visite</h3>
                        <p class="text-xs" style="color: var(--kd-gray-400)">Choisissez votre date et heure préférées</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('client.soumettre-visite', $bien->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="kd-label">Date souhaitée *</label>
                            <input type="date"
                                   name="date_visite"
                                   min="{{ now()->addDay()->toDateString() }}"
                                   value="{{ old('date_visite') }}"
                                   class="kd-input @error('date_visite') border-red-400 @enderror">
                            @error('date_visite')
                                <p class="text-xs mt-1" style="color: var(--kd-danger)">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="kd-label">Heure souhaitée *</label>
                            <input type="time"
                                   name="heure_visite"
                                   value="{{ old('heure_visite', '10:00') }}"
                                   class="kd-input @error('heure_visite') border-red-400 @enderror">
                            @error('heure_visite')
                                <p class="text-xs mt-1" style="color: var(--kd-danger)">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="kd-label">Message à l'agent (optionnel)</label>
                        <textarea name="notes"
                                  rows="3"
                                  placeholder="Ex: Je suis disponible en matinée, je souhaite visiter le balcon..."
                                  class="kd-input resize-none">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="kd-btn kd-btn-primary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Envoyer la demande de visite
                    </button>
                </form>
            </div>
            @endif

            {{-- Visites récentes (admin/agent uniquement) --}}
            @if(!Auth::user()->isClient() && $bien->visites->count() > 0)
            <div class="kd-card">
                <div class="kd-card-title">Visites ({{ $bien->visites->count() }})</div>
                <table class="kd-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bien->visites->take(5) as $visite)
                        <tr>
                            <td>{{ $visite->client->prenom ?? '' }} {{ $visite->client->nom ?? '—' }}</td>
                            <td>{{ $visite->date_visite->format('d/m/Y') }}</td>
                            <td>
                                <span class="kd-badge {{ match($visite->statut->value) {
                                    'realisee'  => 'kd-badge-green',
                                    'annulee'   => 'kd-badge-red',
                                    default     => 'kd-badge-orange',
                                } }}">{{ $visite->statut->label() }}</span>
                            </td>
                            <td>
                                @if($visite->note_client)
                                    <span class="font-semibold text-gold">{{ $visite->note_client }}/5</span>
                                @else
                                    <span style="color: var(--kd-gray-400)">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Sidebar droite --}}
        <div class="space-y-5">

            {{-- CTA Client --}}
            @if(Auth::user()->isClient() && $bien->statut->value === 'disponible')
            <div class="kd-card text-center" style="background: var(--kd-black)">
                <p class="text-sm font-semibold text-white mb-1">Intéressé par ce bien ?</p>
                <p class="text-xs mb-4" style="color: var(--kd-gray-400)">Demandez une visite gratuitement</p>
                <a href="#demande" class="kd-btn kd-btn-primary w-full justify-center"
                   onclick="document.querySelector('form[action*=soumettre]').scrollIntoView({behavior:'smooth'}); return false;">
                    Planifier une visite →
                </a>
            </div>
            @endif

            {{-- Agent --}}
            @if($bien->agent)
            <div class="kd-card">
                <div class="kd-card-title">Agent responsable</div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="kd-avatar">{{ strtoupper(substr($bien->agent->name, 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm" style="color: var(--kd-black)">{{ $bien->agent->name }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $bien->agent->email }}</p>
                    </div>
                </div>
                @if($bien->agent->telephone)
                <div class="flex items-center gap-2 text-sm" style="color: var(--kd-gray-600)">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $bien->agent->telephone }}
                </div>
                @endif
            </div>
            @endif

            {{-- Infos supplémentaires --}}
            <div class="kd-card">
                <div class="kd-card-title">Informations</div>
                <div class="space-y-3 text-sm">
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Référence</span>
                        <span class="font-mono font-semibold text-xs" style="color: var(--kd-gold)">{{ $bien->reference }}</span>
                    </div>
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Vues</span>
                        <span class="font-semibold">{{ $bien->nombre_vues }}</span>
                    </div>
                    @if($bien->annee_construction)
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Année construction</span>
                        <span class="font-semibold">{{ $bien->annee_construction }}</span>
                    </div>
                    @endif
                    @if($bien->date_publication)
                    <div class="kd-stat-mini">
                        <span style="color: var(--kd-gray-400)">Publié le</span>
                        <span class="font-semibold">{{ $bien->date_publication->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Transactions (admin/agent uniquement) --}}
            @if(!Auth::user()->isClient() && $bien->transactions->count() > 0)
            <div class="kd-card">
                <div class="kd-card-title">Transactions ({{ $bien->transactions->count() }})</div>
                @foreach($bien->transactions->take(3) as $transaction)
                <div class="kd-stat-mini">
                    <div>
                        <p class="text-xs font-semibold" style="color: var(--kd-black)">{{ $transaction->reference }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $transaction->type->label() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gold">{{ number_format($transaction->montant, 0, ',', ' ') }}</p>
                        <p class="text-xs" style="color: var(--kd-gray-400)">FCFA</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</x-app-layout>