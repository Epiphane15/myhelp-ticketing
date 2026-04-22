<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MyHelp') }} - Votre Centre de Support</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .hero-section {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .hero-blob {
            position: absolute;
            background: radial-gradient(circle, rgba(79,70,229,0.1) 0%, rgba(255,255,255,0) 70%);
            width: 800px;
            height: 800px;
            border-radius: 50%;
            top: -200px;
            right: -200px;
            z-index: 0;
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-md navbar-light bg-transparent py-4 position-absolute w-100 z-3" style="border: none; backdrop-filter: none;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-4 text-primary animate-fade-in-up" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo MyHelp" height="36" class="me-2" style="object-fit: contain;">
                {{ config('app.name', 'MyHelp') }}
            </a>
            
            <div class="ms-auto animate-fade-in-up delay-1">
                @if (Route::has('login'))
                    <div class="d-flex gap-3 align-items-center">
                        @auth
                            <a href="{{ route('tickets.index') }}" class="fw-semibold text-decoration-none text-dark hover-lift">Mon Espace</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-semibold px-4 rounded-pill">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary fw-semibold px-4 rounded-pill shadow-sm">Inscription</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="hero-section d-flex align-items-center">
        <div class="hero-blob"></div>
        <div class="container hero-content">
            <div class="row align-items-center justify-content-center text-center text-md-start">
                <div class="col-lg-6 animate-fade-in-up delay-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4 fs-6">✨ Simplifiez votre support</span>
                    <h1 class="display-4 fw-bold text-dark mb-4" style="line-height: 1.2;">
                        Gérez vos demandes avec <span class="text-primary">efficacité</span>
                    </h1>
                    <p class="fs-5 text-secondary mb-5">
                        MyHelp vous offre un espace centralisé, rapide et transparent pour échanger avec nos équipes. 
                        Vos problèmes trouvent des solutions, rapidement.
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                        @auth
                            <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-lg px-5 shadow rounded-pill hover-lift">Accéder à mes tickets</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 shadow rounded-pill hover-lift">Commencer maintenant</a>
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 border shadow-sm rounded-pill hover-lift text-dark text-decoration-none fw-medium">Me connecter</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 animate-fade-in-up delay-3 d-none d-md-block">
                    <!-- Dashboard Preview Image or Illustration -->
                    <div class="card border-0 shadow-lg hover-lift rounded-4 overflow-hidden" style="transform: perspective(1000px) rotateY(-5deg); transition: transform 0.5s ease;">
                        <div class="card-header bg-light border-0 py-3 d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-danger" style="width: 12px; height: 12px;"></div>
                            <div class="rounded-circle bg-warning" style="width: 12px; height: 12px;"></div>
                            <div class="rounded-circle bg-success" style="width: 12px; height: 12px;"></div>
                        </div>
                        <div class="card-body p-0 bg-white" style="height: 380px;">
                            <!-- Fake UI -->
                            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                                <h5 class="fw-bold mb-0">Mes Tickets</h5>
                                <span class="badge bg-primary rounded-pill px-3">2 ouverts</span>
                            </div>
                            <div class="p-4 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border border-light">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold" style="width:40px;height:40px;">M1</div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong class="d-block text-dark">Problème d'accès serveur</strong>
                                            <span class="badge bg-warning bg-opacity-10 text-warning">En cours</span>
                                        </div>
                                        <small class="text-muted">Mis à jour aujourd'hui</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm border border-light">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3 fw-bold" style="width:40px;height:40px;">F2</div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong class="d-block text-dark">Contact Facturation</strong>
                                            <span class="badge bg-success bg-opacity-10 text-success">Ouvert</span>
                                        </div>
                                        <small class="text-muted">Créé hier</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
