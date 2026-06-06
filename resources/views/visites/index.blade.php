<x-app-layout>
    <x-slot name="title">Visites</x-slot>
    <x-slot name="subtitle">{{ $visites->total() }} visite(s)</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Visites</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="kd-card mb-6">
        <form method="GET" action="{{ route('web.visites.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="kd-label">Statut</label>
                    <select name="statut" class="kd-input">
                        <option value="">Tous</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut->value }}" {{ request('statut') === $statut->value ? 'selected' : '' }}>
                                {{ $statut->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="kd-label">Du</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="kd-input">
                </div>
                <div>
                    <label class="kd-label">Au</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="kd-input">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="kd-btn kd-btn-primary">Filtrer</button>
                <a href="{{ route('web.visites.index') }}" class="kd-btn kd-btn-outline">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="kd-card">
        <div class="overflow-x-auto">
            <table class="kd-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Bien</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visites as $visite)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="kd-avatar" style="width:28px;height:28px;font-size:10px">
                                    {{ strtoupper(substr($visite->client->prenom ?? 'C', 0, 1)) }}
                                </div>
                                <span class="text-sm">{{ $visite->client->prenom ?? '' }} {{ $visite->client->nom ?? '—' }}</span>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ Str::limit($visite->bien->titre ?? '—', 30) }}</span></td>
                        <td><span class="text-sm font-medium">{{ $visite->date_visite->format('d/m/Y') }}</span></td>
                        <td><span class="text-sm" style="color: var(--kd-gray-400)">{{ $visite->heure_visite?->format('H:i') ?? '—' }}</span></td>
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
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('web.visites.show', $visite->id) }}" class="kd-btn kd-btn-outline py-1 px-3 text-xs">Voir</a>
                                <a href="{{ route('pdf.rapport_visite', $visite->id) }}" target="_blank" class="kd-btn kd-btn-primary py-1 px-3 text-xs">PDF</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8" style="color: var(--kd-gray-400)">Aucune visite</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visites->hasPages())
        <div class="flex items-center justify-between mt-6">
            <p class="text-sm" style="color: var(--kd-gray-400)">{{ $visites->firstItem() }}–{{ $visites->lastItem() }} sur {{ $visites->total() }}</p>
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
    </div>
</x-app-layout>