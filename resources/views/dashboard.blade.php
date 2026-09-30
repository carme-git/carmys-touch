@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
@php
    $f = fn ($n) => number_format($n, 0, ',', ' ') . ' F';
@endphp

<p class="text-muted mb-3">{{ ucfirst($moisLabel) }}</p>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-app p-3 h-100">
            <div class="text-muted small">Chiffre d'affaires</div>
            <div class="fs-4 fw-bold">{{ $f($ca) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-app p-3 h-100">
            <div class="text-muted small">Bénéfice réel</div>
            <div class="fs-4 fw-bold {{ $benefice < 0 ? 'text-danger' : '' }}">{{ $f($benefice) }}</div>
            <div class="text-muted small">après achats et {{ $f($depenses) }} de dépenses</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-app p-3 h-100">
            <div class="text-muted small">Ventes du mois</div>
            <div class="fs-4 fw-bold">{{ $nbVentes }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-app p-3 h-100">
            <div class="text-muted small">Aujourd'hui</div>
            <div class="fs-4 fw-bold">{{ $nbVentesJour }} vente(s)</div>
            <div class="text-muted small">{{ $f($caJour) }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="card-app p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Impayés</h5>
                <span class="fw-bold text-danger">{{ $f($totalImpayes) }}</span>
            </div>
            <table class="table table-app mb-0">
                <thead><tr><th>Cliente</th><th>Depuis</th><th class="text-end">Reste</th><th></th></tr></thead>
                <tbody>
                @forelse($impayes->take(8) as $vente)
                    @php $jours = (int) abs($vente->date_vente->diffInDays(now())); @endphp
                    <tr>
                        <td>{{ $vente->client->nom_complet ?? 'Sans cliente' }}</td>
                        <td class="{{ $jours > 14 ? 'text-danger fw-semibold' : '' }}">{{ $jours }} j</td>
                        <td class="text-end">{{ $f($vente->reste_a_payer) }}</td>
                        <td class="text-end"><a href="{{ route('ventes.show', $vente) }}" class="icon-btn"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Aucun impayé. Tout est à jour.</td></tr>
                @endforelse
                </tbody>
            </table>
            @if($impayes->count() > 8)
                <div class="text-muted small mt-2">+ {{ $impayes->count() - 8 }} autre(s) impayé(s) plus récent(s).</div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-app p-4 h-100">
            <h5 class="mb-3">Top produits du mois</h5>
            <table class="table table-app mb-0">
                <tbody>
                @forelse($topProduits as $p)
                    <tr>
                        <td>{{ $p->nom }}</td>
                        <td class="text-end">{{ $p->quantite }} vendu(s)</td>
                        <td class="text-end">{{ $f($p->total) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-muted py-3">Aucune vente ce mois-ci.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card-app p-4 h-100">
            <h5 class="mb-3">Stock bas</h5>
            <table class="table table-app mb-0">
                <tbody>
                @forelse($stockBas as $p)
                    <tr>
                        <td>{{ $p->nom }}</td>
                        <td class="text-end"><span class="badge-app badge-impaye">{{ $p->quantite_stock }} / seuil {{ $p->seuil_alerte }}</span></td>
                    </tr>
                @empty
                    <tr><td class="text-center text-muted py-3">Aucun produit sous son seuil.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-app p-4 h-100">
            <div class="d-flex justify-content-between mb-1">
                <h5 class="mb-0">Stock dormant</h5>
                <span class="fw-bold">{{ $f($argentImmobilise) }}</span>
            </div>
            <div class="text-muted small mb-3">Rien depuis 30 jours : argent immobilisé dans ces parfums.</div>
            <table class="table table-app mb-0">
                <tbody>
                @forelse($dormants as $p)
                    <tr>
                        <td>{{ $p->nom }}</td>
                        <td>{{ $p->quantite_stock }} en stock</td>
                        <td class="text-end">{{ (int) abs($p->derniere_activite->diffInDays(now())) }} j</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-muted py-3">Aucun stock dormant.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection