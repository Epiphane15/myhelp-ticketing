@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 animate-fade-in-up">
        <div class="col-md-12 d-flex justify-content-between align-items-end">
            <div>
                <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">← Retour aux tickets</a>
                <h2 class="fw-bold mb-0">Nouveau Ticket</h2>
            </div>
        </div>
    </div>

    <div class="row justify-content-center animate-fade-in-up delay-1">
        <div class="col-md-10">
            <div class="card shadow hover-lift border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-semibold">Sujet de votre demande</label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Ex: Problème d'accès à la plateforme..." required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mt-4 mt-md-0">
                                <label for="priority_id" class="form-label fw-semibold">Niveau d'urgence</label>
                                <select class="form-select form-select-lg @error('priority_id') is-invalid @enderror" id="priority_id" name="priority_id" required>
                                    <option value="" disabled selected>Choisir...</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                    @endforeach
                                </select>
                                @error('priority_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description détaillée</label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" id="description" name="description" rows="7" placeholder="Veuillez décrire le problème rencontré avec un maximum de détails..." required>{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <a href="{{ route('tickets.index') }}" class="btn btn-light btn-lg me-3 fw-medium">Annuler</a>
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow px-4">Soumettre mon Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
