@extends('layouts.app')
@section('title', $client->nom_complet)

@section('content')
@php $labels = ['paye' => 'Payé', 'partiel' => 'Partiel', 'impaye' => 'Impayé']; @endphp

<div class="card-app mb-3 d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h2 class="page-title mb-1">{{ $client->nom_complet }}</h2>
        <div style="color:var(--texte-doux);">
            @if($client->telephone)<i class="bi bi-telephone"></i> {{ $client->telephone }}&nbsp;&nbsp;@endif
            @if($client->email)<i class="bi bi-envelope"></i> {{ $client->email }}&nbsp;&nbsp;@endif
            @if($client->adresse)<i class="bi bi-geo-alt"></i> {{ $client->adresse }}@endif
        </div>
    </div>
    <div class="text-end">
        @if($client->solde_impaye > 0)
            <span class="badge-app badge-impaye" style="font-size:13px; padding:6px 14px;">
                Doit {{ number_format($client->solde_impaye, 0, ',', ' ') }} F
            </span>
        @else
            <span class="badge-app badge-paye" style="font-size:13px; padding:6px 14px;">À jour</span>
        @endif
        <div class="mt-2">
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-secondary btn-sm">Modifier</a>
        </div>
    </div>
</div>

<div class="card-app p-0" style="overflow:hidden;">
    <div style="padding:14px 16px; font-weight:500;">Historique des commandes</div>
    <table class="table-app">
        <thead>
            <tr><th>Date</th><th>Total</th><th>Payé</th><th>Reste</th><th>Statut</th></tr>
        </thead>
        <tbody>
        @forelse($client->ventes as $v)
            <tr>
                <td>{{ $v->date_vente->format('d/m/Y') }}</td>
                <td>{{ number_format($v->montant_total, 0, ',', ' ') }} F</td>
                <td>{{ number_format($v->montant_paye, 0, ',', ' ') }} F</td>
                <td>{{ number_format($v->reste_a_payer, 0, ',', ' ') }} F</td>
                <td>
                    <span class="badge-app badge-{{ $v->statut_paiement }}">
                        {{ $labels[$v->statut_paiement] ?? $v->statut_paiement }}
                    </span>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-4" style="color:var(--texte-doux);">Aucune commande pour le moment</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection