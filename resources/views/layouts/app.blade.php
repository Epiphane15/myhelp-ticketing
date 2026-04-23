<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-els">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MyHelp') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
    <div id="app" class="flex-grow-1">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center fw-bold text-primary animate-fade-in-up" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="32" class="me-2" style="object-fit: contain;">
                    {{ config('app.name', 'MyHelp') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item animate-fade-in-up delay-1">
                                <a class="nav-link" href="{{ route('tickets.index') }}">Mes Tickets</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto animate-fade-in-up delay-2">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Inscription') }}</a>
                                </li>
                            @endif
                        @else
                            @php
                                $unread = Auth::user()->unreadNotifications;
                            @endphp
                            <li class="nav-item dropdown me-2">
                                <a id="notifDropdown" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    🔔
                                    @if($unread->count() > 0)
                                        <span class="position-absolute top-10 start-80 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                            {{ $unread->count() }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 rounded-3" aria-labelledby="notifDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    <div class="dropdown-header bg-light border-bottom d-flex justify-content-between align-items-center py-2">
                                        <strong class="text-dark">Notifications</strong>
                                        @if($unread->count() > 0)
                                            <a href="{{ route('notifications.read') }}" class="text-primary text-decoration-none small fw-medium">Tout lire</a>
                                        @endif
                                    </div>
                                    @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                        <div class="dropdown-item border-bottom p-3 {{ $notification->read_at ? 'bg-light text-muted opacity-75' : 'bg-white' }}" style="white-space: normal;">
                                            <strong class="d-block text-dark" style="font-size: 0.9rem;">{{ $notification->data['title'] ?? 'Alerte' }}</strong>
                                            <span class="d-block mt-1 text-secondary" style="font-size: 0.85rem;">{{ $notification->data['message'] ?? '' }}</span>
                                            <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">🕒 {{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    @empty
                                        <div class="dropdown-item text-center text-muted p-4">
                                            Aucune notification pour le moment. ☕
                                        </div>
                                    @endforelse
                                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary fw-semibold border-top py-2 bg-light">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="badge bg-primary text-white me-1">{{ ucfirst(Auth::user()->role) }}</span>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdown">
                                    <h6 class="dropdown-header">Mon Compte</h6>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger fw-medium" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="me-2">🚪</i> {{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>
    </div>
    
    <footer class="mt-auto py-4 bg-white border-top text-center text-muted small animate-fade-in-up delay-3">
        <div class="container">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyHelp') }}. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
