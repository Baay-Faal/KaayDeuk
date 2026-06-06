<x-app-layout>
    <x-slot name="title">Notifications</x-slot>
    <x-slot name="subtitle">{{ $nonLues }} non lue(s)</x-slot>

    <div class="kd-page-header">
        <div>
            <h2 class="kd-page-title">Notifications</h2>
            <div class="kd-gold-line mt-2"></div>
        </div>
        @if($nonLues > 0)
        <form method="POST" action="{{ route('web.notifications.lire_tout') }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="kd-btn kd-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    {{-- Filtres --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('web.notifications.index') }}"
           class="kd-btn {{ $filtre === 'toutes' ? 'kd-btn-dark' : 'kd-btn-outline' }}">
            Toutes
        </a>
        <a href="{{ route('web.notifications.index', ['filtre' => 'non_lues']) }}"
           class="kd-btn {{ $filtre === 'non_lues' ? 'kd-btn-dark' : 'kd-btn-outline' }}">
            Non lues
            @if($nonLues > 0)
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs" style="background: var(--kd-gold); color: var(--kd-black)">{{ $nonLues }}</span>
            @endif
        </a>
        <a href="{{ route('web.notifications.index', ['filtre' => 'lues']) }}"
           class="kd-btn {{ $filtre === 'lues' ? 'kd-btn-dark' : 'kd-btn-outline' }}">
            Lues
        </a>
    </div>

    <div class="kd-card p-0 overflow-hidden">
        @forelse($notifications as $notification)
        <div class="kd-notif-item {{ !$notification->read_at ? 'unread' : '' }}" style="padding: 1rem 1.5rem">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                 style="background: {{ $notification->read_at ? 'var(--kd-gray-100)' : 'rgba(212,175,55,0.15)' }}">
                <svg class="w-5 h-5" style="color: {{ $notification->read_at ? 'var(--kd-gray-400)' : 'var(--kd-gold)' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold mb-0.5" style="color: var(--kd-black)">
                            {{ $notification->data['titre'] ?? 'Notification' }}
                            @if(!$notification->read_at)
                                <span class="inline-block w-2 h-2 rounded-full ml-1" style="background: var(--kd-gold)"></span>
                            @endif
                        </p>
                        <p class="text-sm" style="color: var(--kd-gray-600)">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-xs" style="color: var(--kd-gray-400)">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        @if(!$notification->read_at)
                        <form method="POST" action="{{ route('web.notifications.lire', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="kd-btn kd-btn-outline py-1 px-3 text-xs">
                                Marquer lu
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="kd-empty py-16">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="kd-empty-title">Aucune notification</p>
            <p class="kd-empty-text">Vous êtes à jour !</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm" style="color: var(--kd-gray-400)">{{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} sur {{ $notifications->total() }}</p>
        <div class="kd-pagination">
            @if($notifications->onFirstPage())
                <span class="kd-page-btn opacity-40">←</span>
            @else
                <a href="{{ $notifications->previousPageUrl() }}" class="kd-page-btn">←</a>
            @endif
            @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="kd-page-btn {{ $page === $notifications->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}" class="kd-page-btn">→</a>
            @else
                <span class="kd-page-btn opacity-40">→</span>
            @endif
        </div>
    </div>
    @endif
</x-app-layout>