@extends('layouts.app')

@section('title', 'Achat du ' . $achat->date_achat->format('d/m/Y'))

@section('content')
@include('partials.alertes')

<div class="card-app p-4 mb-3">
    <div class="d-flex justify-content-between">
        <div>
            <div class="text-muted small">Type</div>
            {{ $achat->type === 'commande_client' ? 'Sur commande' : "Stock d'avance" }}
        </div>
        <div>
            <div class="text-muted small">Fournisseur</div>
            {{ $achat->fournisseur->nom ?? 'Non précisé' }}
        </div>
        <div>
            <div class="text-muted small">Vente concernée</div>
            @if($achat->vente)
                <a href="{{ route('ventes.show', $achat->vente) }}" class="text-decoration-none">
                    #{{ $achat->vente->id }} · {{ $achat->vente->client->nom_complet ?? 'Sans cliente' }}
                </a>
            @else
                —
            @endif
        </div>
    </div>
</div>

<div class="card-app mb-3">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Parfum</th><th>Qté</th><th>Prix d'achat</th><th class="text-end">Sous-total</th></tr>
        </thead>
        <tbody>
        @foreach($achat->detailsAchats as $ligne)
            <tr>
                <td>{{ $ligne->produit->nom ?? '—' }}</td>
                <td>{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }} F</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="card-app p-4 ms-auto" style="max-width: 360px;">
    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>{{ number_format($achat->montant_total, 0, ',', ' ') }} F</span></div>
</div>
@endsection