@extends('layouts.app')

@section('title', 'Achats')

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('achats.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouvel achat</a>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Date</th><th>Type</th><th>Fournisseur</th><th class="text-end">Total</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($achats as $achat)
            <tr>
                <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                <td>{{ $achat->type === 'commande_client' ? 'Sur commande' : "Stock d'avance" }}</td>
                <td>{{ $achat->fournisseur->nom ?? 'Non précisé' }}</td>
                <td class="text-end">{{ number_format($achat->montant_total, 0, ',', ' ') }} F</td>
                <td class="text-end">
                    <a href="{{ route('achats.show', $achat) }}" class="icon-btn"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun achat enregistré.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $achats->links('pagination::bootstrap-5') }}</div>
@endsection