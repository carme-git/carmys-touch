@extends('layouts.app')

@section('title', $fournisseur->nom)

@section('content')
@include('partials.alertes')

<div class="card-app p-4 mb-3">
    <div class="d-flex justify-content-between">
        <div>
            <div class="text-muted small">Téléphone</div>
            {{ $fournisseur->telephone ?: '—' }}
        </div>
        <div>
            <div class="text-muted small">E-mail</div>
            {{ $fournisseur->email ?: '—' }}
        </div>
        <div>
            <div class="text-muted small">Adresse</div>
            {{ $fournisseur->adresse ?: '—' }}
        </div>
        <div>
            <div class="text-muted small">Total acheté chez lui</div>
            <span class="fw-bold">{{ number_format($achats->sum('montant_total'), 0, ',', ' ') }} F</span>
        </div>
    </div>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Date</th><th>Type</th><th class="text-end">Total</th><th class="text-end"></th></tr>
        </thead>
        <tbody>
        @forelse($achats as $achat)
            <tr>
                <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                <td>{{ $achat->type === 'commande_client' ? 'Sur commande' : "Stock d'avance" }}</td>
                <td class="text-end">{{ number_format($achat->montant_total, 0, ',', ' ') }} F</td>
                <td class="text-end"><a href="{{ route('achats.show', $achat) }}" class="icon-btn"><i class="bi bi-eye"></i></a></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucun achat chez ce fournisseur.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection