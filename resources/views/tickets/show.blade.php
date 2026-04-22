@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="row mb-4 animate-fade-in-up">
        <div class="col-12">
            <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted fw-medium">← Retour aux tickets</a>
        </div>
    </div>

    <div class="row">
        <!-- Colonne Info Latérale -->
        <div class="col-lg-4 order-lg-2 mb-4 animate-fade-in-up delay-1">
            <div class="card shadow-sm border-0 hover-lift rounded-4 sticky-top" style="top: 100px; z-index: 10;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Informations</h5>
                    
                    <div class="mb-4">
                        <span class="text-muted d-block small mb-1 text-uppercase fw-semibold">Statut actuel</span>
                        @php
                            $statuses = [
                                'ouvert' => 'success',
                                'assigne' => 'primary',
                                'en_cours' => 'warning',
                                'en_attente' => 'secondary',
                                'resolu' => 'dark'
                            ];
                            $badgeClass = $statuses[$ticket->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} px-3 py-2 fs-6">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted d-block small mb-1 text-uppercase fw-semibold">Urgence</span>
                        <div class="d-inline-flex align-items-center">
                            <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: {{ $ticket->priority->color ?? '#6c757d' }};"></div>
                            <span class="fw-medium text-dark">{{ $ticket->priority->name ?? 'Aucune' }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted d-block small mb-1 text-uppercase fw-semibold">Demandeur</span>
                        <div class="d-flex align-items-center mt-2">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 fw-bold text-secondary" style="width: 40px; height: 40px; font-size: 1rem;">
                                {{ substr($ticket->user->name, 0, 2) }}
                            </div>
                            <div>
                                <strong class="d-block text-dark">{{ $ticket->user->name }}</strong>
                                <span class="text-muted small">{{ $ticket->user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted d-block small mb-1 text-uppercase fw-semibold">Agent Assigné</span>
                        <div class="p-3 bg-light rounded-3 mt-2">
                            <strong>{{ $ticket->agent->name ?? 'Aucun agent assigné' }}</strong>
                        </div>
                    </div>

                    @can('update', $ticket)
                        @if($ticket->status !== 'resolu')
                            <hr class="my-4 opacity-10">
                            <h6 class="fw-bold mb-3">🛠 Administration</h6>
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <select name="agent_id" class="form-select border-primary" required>
                                        <option value="" disabled selected>Assig. Rapide</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $ticket->agent_id == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary fw-medium" type="submit">OK</button>
                                </div>
                            </form>

                            <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-dark w-100 fw-semibold" onclick="return confirm('Attention: Voullez-vous vraiment clôturer définitivement ce ticket ?')">Clôturer ce Ticket</button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>

        <!-- Colonne Chat Conversation -->
        <div class="col-lg-8 order-lg-1">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 animate-fade-in-up">
                    {{ session('success') }}
                </div>
            @endif

            <div class="px-3 px-md-4 mb-5 animate-fade-in-up delay-2">
                <h2 class="fw-bold mb-2 text-dark">{{ $ticket->title }}</h2>
                <p class="text-muted">Ticket ouvert le {{ $ticket->created_at->format('d M Y à H:i') }}</p>
                
                <div class="mt-4 p-4 p-md-5 bg-white rounded-4 shadow-sm border-0">
                    <p class="mb-0 fs-5 text-secondary" style="white-space: pre-wrap; line-height: 1.7;">{{ $ticket->description }}</p>
                </div>
            </div>

            <!-- Thread -->
            <div class="px-0 px-md-4 mb-4">
                <h5 class="fw-bold text-muted mb-4 px-3 px-md-0">Historique des échanges</h5>

                @php $delay = 2; @endphp
                @foreach($ticket->messages as $message)
                    @php 
                        $delay++; 
                        $isOwner = $message->user_id === $ticket->user_id;
                    @endphp
                    <div class="d-flex mb-4 {{ $isOwner ? 'justify-content-end' : 'justify-content-start' }} animate-fade-in-up" style="animation-delay: 0.{{ $delay }}s">
                        <div class="d-flex flex-column {{ $isOwner ? 'align-items-end' : 'align-items-start' }}" style="max-width: 80%;">
                            <span class="text-muted small mb-1 px-1">
                                <strong>{{ $message->user->name }}</strong> • {{ $message->created_at->format('d/m H:i') }}
                            </span>
                            <div class="chat-bubble {{ $isOwner ? 'chat-right text-end' : 'chat-left' }}">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Réponse form -->
            <div class="px-0 px-md-4 mt-5 animate-fade-in-up" style="animation-delay: 0.{{ $delay + 1 }}s">
                @if($ticket->status !== 'resolu')
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('tickets.reply', $ticket) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-bold">Votre Réponse</label>
                                    <textarea name="message" id="message" rows="5" class="form-control form-control-lg bg-light border-0" required placeholder="Tapez votre message ici..."></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm px-5">Envoyer au support</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert bg-white border shadow-sm text-center p-5 rounded-4 text-muted">
                        <div class="fs-1 mb-3">🔒</div>
                        <h4 class="fw-bold">Ticket Clôturé</h4>
                        <p class="mb-0">Cette discussion est fermée et ne peut plus recevoir de nouvelles réponses.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
