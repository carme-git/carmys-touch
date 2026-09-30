@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-between mb-3">
    <form method="GET" action="{{ route('fournisseurs.index') }}" style="width: 420px;">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un fournisseur">
    </form>
    <a href="{{ route('fournisseurs.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouveau fournisseur</a>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Fournisseur</th><th>Téléphone</th><th>Achats</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($fournisseurs as $fournisseur)
            <tr>
                <td class="fw-semibold">{{ $fournisseur->nom }}</td>
                <td>{{ $fournisseur->telephone ?: '—' }}</td>
                <td>{{ $fournisseur->achats_count }}</td>
                <td class="text-end">
                    <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="icon-btn"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="icon-btn"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('fournisseurs.destroy', $fournisseur) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer ce fournisseur ?')">
                        @csrf @method('DELETE')
                        <button class="icon-btn border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucun fournisseur enregistré.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection