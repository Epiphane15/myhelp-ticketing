@extends('layouts.app')

@section('content')
<!-- Auto-refresh silencieux toutes les 30 secondes pour mettre à jour les disparitions automatiquement -->
<script>
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>

<div class="container">
    <div class="row mb-5 align-items-center animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-1">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
                    Espace Support
                @else
                    Mes Demandes d'Assistance
                @endif
            </h1>
            <p class="text-muted mb-0">Découvrez et gérez vos tickets en un clin d'œil.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @can('create', App\Models\Ticket::class)
                <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <span class="fs-5 me-1">+</span> Créer un Ticket
                </a>
            @endcan
        </div>
    </div>

    @if(auth()->user()->role !== 'client')
        <div class="card shadow-sm border-0 mb-4 animate-fade-in-up">
            <div class="card-body">
                <form action="{{ route('tickets.index') }}" method="GET" class="row gx-3 gy-2 align-items-center">
                    <div class="col-md-4">
                        <select class="form-select form-select-lg" id="priority_id" name="priority_id">
                            <option value="">Toutes les priorités</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-lg" id="status" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="ouvert" {{ request('status') == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                            <option value="assigne" {{ request('status') == 'assigne' ? 'selected' : '' }}>Assigné</option>
                            <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="resolu" {{ request('status') == 'resolu' ? 'selected' : '' }}>Résolu</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success animate-fade-in-up delay-1 border-0 shadow-sm rounded-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 hover-lift animate-fade-in-up delay-1">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Sujet</th>
                            <th class="py-3">Priorité</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3">Demandeur</th>
                            <th class="py-3">Assigné</th>
                            <th class="py-3">Créé le</th>
                            <th class="pe-4 py-3 text-end">Options</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $ticket->id }}</td>
                                <td class="fw-semibold text-dark">{{ $ticket->title }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $ticket->priority->color ?? '#64748b' }}20; color: {{ $ticket->priority->color ?? '#64748b' }};">
                                        • {{ $ticket->priority->name ?? 'Aucune' }}
                                    </span>
                                </td>
                                <td>
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
                                    <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ substr($ticket->user->name, 0, 2) }}
                                        </div>
                                        <span class="text-secondary">{{ $ticket->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($ticket->agent)
                                        <span class="text-dark fw-medium">{{ $ticket->agent->name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Non assigné</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $ticket->created_at->format('d M Y') }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-light text-primary fw-semibold px-3 rounded-pill hover-lift">Ouvrir ➔</a>
                                        @if($ticket->status === 'resolu')
                                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3 rounded-pill hover-lift" onclick="return confirm('Confirmer la suppression ? Cela cachera ce ticket de votre interface.')">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-3" style="font-size: 3rem;">📂</div>
                                    <h5 class="fw-bold">Aucun ticket trouvé</h5>
                                    <p class="text-secondary mb-0">C'est plutôt calme par ici, tout semble fonctionner !</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
