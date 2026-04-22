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
