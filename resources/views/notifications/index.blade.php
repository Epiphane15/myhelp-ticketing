@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="row animate-fade-in-up">
        <div class="col-8">
            <h2 class="fw-bold mb-1">Centre de Notifications</h2>
            <p class="text-muted">Retrouvez tout l'historique des événements de vos tickets.</p>
        </div>
        <div class="col-4 text-end">
             <a href="{{ route('notifications.read') }}" class="btn btn-outline-primary rounded-pill shadow-sm">Tout marquer comme lu</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-10 offset-md-1 animate-fade-in-up delay-1">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded-4">
                        @forelse($notifications as $notification)
                            <a href="{{ route('tickets.show', $notification->data['ticket_id'] ?? 0) }}" class="list-group-item list-group-item-action p-4 {{ $notification->read_at ? 'bg-light text-muted' : 'bg-white' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 fw-bold {{ $notification->read_at ? 'text-secondary' : 'text-primary' }}">{{ $notification->data['title'] ?? 'Alerte' }}</h5>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->data['message'] ?? 'Aucun détail' }}</p>
                            </a>
                        @empty
                            <div class="text-center p-5 text-muted">
                                <h4 class="mb-0">☕ Votre boîte est vide.</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
