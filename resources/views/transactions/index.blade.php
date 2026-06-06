<x-app-layout>
    <x-slot name="title">Transactions</x-slot>
    <x-slot name="subtitle">{{ $transactions->total() }} transaction(s)</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Transactions</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="kd-card mb-6">
        <form method="GET" action="{{ route('web.transactions.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="kd-label">Type</label>
                    <select name="type" class="kd-input">
                        <option value="">Tous</option>
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" {{ request('type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="kd-label">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="kd-input">
                </div>
                <div>
                    <label class="kd-label">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="kd-input">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="kd-btn kd-btn-primary">Filtrer</button>
                <a href="{{ route('web.transactions.index') }}" class="kd-btn kd-btn-outline">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="kd-card">
        <div class="overflow-x-auto">
            <table class="kd-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Bien</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Commission</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td><span class="font-mono text-xs font-semibold text-gold">{{ $transaction->reference }}</span></td>
                        <td><span class="text-sm font-medium">{{ Str::limit($transaction->bien->titre ?? '—', 30) }}</span></td>
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
                        <td><span class="font-semibold text-sm">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</span></td>
                        <td><span class="text-sm" style="color: var(--kd-gray-600)">{{ number_format($transaction->commission_agence, 0, ',', ' ') }} FCFA</span></td>
                        <td><span class="text-sm" style="color: var(--kd-gray-400)">{{ $transaction->date_signature?->format('d/m/Y') ?? '—' }}</span></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('web.transactions.show', $transaction->id) }}" class="kd-btn kd-btn-outline py-1 px-3 text-xs">Voir</a>
                                <a href="{{ route('pdf.recu', $transaction->id) }}" target="_blank" class="kd-btn kd-btn-primary py-1 px-3 text-xs">PDF</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-8" style="color: var(--kd-gray-400)">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="flex items-center justify-between mt-6">
            <p class="text-sm" style="color: var(--kd-gray-400)">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} sur {{ $transactions->total() }}</p>
            <div class="kd-pagination">
                @if($transactions->onFirstPage())
                    <span class="kd-page-btn opacity-40">←</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="kd-page-btn">←</a>
                @endif
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="kd-page-btn {{ $page === $transactions->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="kd-page-btn">→</a>
                @else
                    <span class="kd-page-btn opacity-40">→</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-app-layout>