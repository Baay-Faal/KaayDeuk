<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Kaay Deuk</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-data="sidebar()">

    {{-- ── Overlay mobile ── --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        style="display:none"
    ></div>

    {{-- ── SIDEBAR ── --}}
    <aside
        class="kd-sidebar"
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        {{-- Logo --}}
        <div class="kd-logo">
            <div class="w-9 h-9 rounded-xl bg-gold flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </div>
            <span class="kd-logo-text">KAAY <span>DEUK</span></span>
        </div>

        {{-- Navigation --}}
        <nav class="kd-nav">

            <p class="kd-nav-section">Principal</p>

            <a href="{{ route('web.dashboard') }}"
               class="kd-nav-link {{ request()->routeIs('web.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <p class="kd-nav-section">Immobilier</p>

            <a href="{{ route('web.biens.index') }}"
               class="kd-nav-link {{ request()->routeIs('web.biens.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Biens
            </a>

            <a href="{{ route('web.clients.index') }}"
               class="kd-nav-link {{ request()->routeIs('web.clients.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clients
            </a>

            <a href="{{ route('web.visites.index') }}"
               class="kd-nav-link {{ request()->routeIs('web.visites.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Visites
            </a>

            <a href="{{ route('web.transactions.index') }}"
               class="kd-nav-link {{ request()->routeIs('web.transactions.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Transactions
            </a>

            <p class="kd-nav-section">Outils</p>

            <a href="{{ route('web.notifications.index') }}"
               class="kd-nav-link {{ request()->routeIs('web.notifications.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
            </a>

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-4 py-4" style="border-top: 1px solid rgba(212,175,55,0.2)">
            <div class="flex items-center gap-3">
                <div class="kd-avatar text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom ?? '', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs truncate" style="color: var(--kd-gray-400)">{{ Auth::user()->role->label() }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── CONTENU PRINCIPAL ── --}}
    <div class="kd-main">

        {{-- Topbar --}}
        <header class="kd-topbar" x-data="notifications()">
            <div class="flex items-center gap-4">
                {{-- Bouton hamburger mobile --}}
                <button @click="$dispatch('toggle-sidebar')" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Titre de la page --}}
                <div>
                    <h1 class="text-lg font-bold" style="color: var(--kd-black)">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                    @isset($subtitle)
                        <p class="text-xs" style="color: var(--kd-gray-400)">{{ $subtitle }}</p>
                    @endisset
                </div>
            </div>

            <div class="flex items-center gap-3">

                {{-- Notifications --}}
                <div class="relative" @click.outside="open = false">
                    <button @click="toggle()" class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" style="color: var(--kd-gray-600)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="count > 0" class="kd-notif-badge" x-text="count"></span>
                    </button>

                    {{-- Dropdown notifications --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-12 w-80 rounded-2xl shadow-xl z-50 overflow-hidden"
                        style="background: var(--kd-white); border: 1px solid var(--kd-gray-200)"
                        style="display:none"
                    >
                        {{-- Header dropdown --}}
                        <div class="flex items-center justify-between px-4 py-3" style="border-bottom: 1px solid var(--kd-gray-100)">
                            <span class="text-sm font-semibold" style="color: var(--kd-black)">Notifications</span>
                            <button @click="marquerTout()" class="text-xs font-medium" style="color: var(--kd-gold)">
                                Tout marquer lu
                            </button>
                        </div>

                        {{-- Liste --}}
                        <div class="max-h-72 overflow-y-auto">
                            <template x-if="loading">
                                <div class="flex items-center justify-center py-8">
                                    <div class="w-6 h-6 border-2 border-gold border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </template>

                            <template x-if="!loading && items.length === 0">
                                <div class="flex flex-col items-center justify-center py-8 text-center px-4">
                                    <svg class="w-10 h-10 mb-2" style="color: var(--kd-gray-200)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                    </svg>
                                    <p class="text-sm" style="color: var(--kd-gray-400)">Aucune notification</p>
                                </div>
                            </template>

                            <template x-for="notif in items" :key="notif.id">
                                <div class="kd-notif-item" :class="!notif.lu ? 'unread' : ''">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(212,175,55,0.15)">
                                        <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold mb-0.5" style="color: var(--kd-black)" x-text="notif.data.titre"></p>
                                        <p class="text-xs" style="color: var(--kd-gray-400)" x-text="notif.data.message"></p>
                                    </div>
                                    <button @click="marquerLue(notif.id)" class="p-1 rounded-lg hover:bg-gray-100 flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" style="color: var(--kd-gray-400)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 py-3" style="border-top: 1px solid var(--kd-gray-100)">
                            <a href="{{ route('web.notifications.index') }}" class="text-xs font-medium" style="color: var(--kd-gold)">
                                Voir toutes les notifications →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Profil dropdown --}}
                <div class="relative" x-data="dropdown()" @click.outside="close()">
                    <button @click="toggle()" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="kd-avatar text-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom ?? '', 0, 1)) }}
                        </div>
                        <svg class="w-4 h-4" style="color: var(--kd-gray-400)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 top-12 w-48 rounded-2xl shadow-xl z-50 py-1 overflow-hidden"
                        style="background: var(--kd-white); border: 1px solid var(--kd-gray-200); display:none"
                    >
                        <div class="px-4 py-3" style="border-bottom: 1px solid var(--kd-gray-100)">
                            <p class="text-sm font-semibold" style="color: var(--kd-black)">{{ Auth::user()->name }}</p>
                            <p class="text-xs" style="color: var(--kd-gray-400)">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors" style="color: var(--kd-gray-600)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mon profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors" style="color: var(--kd-danger)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Contenu de la page --}}
        <main class="p-6 kd-fade-in">
            {{ $slot }}
        </main>
    </div>

    {{-- Toggle sidebar mobile via event --}}
    <script>
        document.addEventListener('toggle-sidebar', () => {
            window.dispatchEvent(new CustomEvent('alpine:toggle-sidebar'));
        });
    </script>
    @stack('scripts')

</body>
</html>