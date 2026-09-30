@extends('layouts.app')

@section('title', 'Dépenses')

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-between align-items-end mb-3">
    <form method="GET" action="{{ route('depenses.index') }}">
        <label class="form-label small text-muted mb-1">Mois</label>
        <input type="month" name="mois" value="{{ $mois }}" class="form-control" onchange="this.form.submit()">
    </form>
    <a href="{{ route('depenses.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouvelle dépense</a>
</div>

<div class="card-app p-3 mb-3 d-flex justify-content-between">
    <span>Total des dépenses du mois</span>
    <strong>{{ number_format($total, 0, ',', ' ') }} F</strong>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Date</th><th>Catégorie</th><th>Description</th><th class="text-end">Montant</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($depenses as $depense)
            <tr>
                <td>{{ $depense->date_depense->format('d/m/Y') }}</td>
                <td>{{ $depense->categorie }}</td>
                <td>{{ $depense->description ?: '—' }}</td>
                <td class="text-end">{{ number_format($depense->montant, 0, ',', ' ') }} F</td>
                <td class="text-end">
                    <a href="{{ route('depenses.edit', $depense) }}" class="icon-btn"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('depenses.destroy', $depense) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer cette dépense ?')">
                        @csrf @method('DELETE')
                        <button class="icon-btn border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucune dépense ce mois-ci.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection