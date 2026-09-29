@extends('layouts.app')

@section('title', 'Ventes')

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('ventes.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouvelle vente</a>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Payé</th>
                <th>Reste</th>
                <th>Statut</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($ventes as $vente)
            <tr>
                <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                <td>{{ $vente->client->nom_complet ?? '—' }}</td>
                <td>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</td>
                <td>{{ number_format($vente->montant_paye, 0, ',', ' ') }} F</td>
                <td>{{ number_format($vente->reste_a_payer, 0, ',', ' ') }} F</td>
                <td><span class="badge-app badge-{{ $vente->statut_paiement }}">{{ ucfirst($vente->statut_paiement) }}</span></td>
                <td class="text-end">
                    <a href="{{ route('ventes.show', $vente) }}" class="icon-btn"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Aucune vente enregistrée.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $ventes->links('pagination::bootstrap-5') }}</div>
@endsection