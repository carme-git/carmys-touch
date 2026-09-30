@extends('layouts.app')

@section('title', 'Vente du ' . $vente->date_vente->format('d/m/Y'))

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('ventes.facture', $vente) }}" target="_blank" class="btn btn-rose">
        <i class="bi bi-file-earmark-pdf"></i> Facture PDF
    </a>
</div>

<div class="card-app p-4 mb-3">
    <div class="d-flex justify-content-between">
        <div>
            <div class="text-muted small">Cliente</div>
            @if($vente->client)
                <a href="{{ route('clients.show', $vente->client) }}" class="fw-semibold text-decoration-none">{{ $vente->client->nom_complet }}</a>
            @else
                —
            @endif
        </div>
        <div>
            <div class="text-muted small">Livraison</div>
            {{ $vente->mode_livraison === 'service' ? 'Par un service' : 'Par moi-même' }}
        </div>
        <div>
            <div class="text-muted small">Statut</div>
            <span class="badge-app badge-{{ $vente->statut_paiement }}">{{ ucfirst($vente->statut_paiement) }}</span>
        </div>
    </div>
</div>

<div class="card-app mb-3">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Parfum</th><th>Qté</th><th>Prix unitaire</th><th>Remise</th><th class="text-end">Sous-total</th></tr>
        </thead>
        <tbody>
        @foreach($vente->detailsVentes as $ligne)
            <tr>
                <td>{{ $ligne->produit->nom ?? '—' }}</td>
                <td>{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                <td>{{ number_format($ligne->remise, 0, ',', ' ') }} F</td>
                <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }} F</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="card-app p-4 ms-auto" style="max-width: 360px;">
    <div class="d-flex justify-content-between"><span>Livraison</span><span>{{ number_format($vente->frais_livraison, 0, ',', ' ') }} F</span></div>
    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span></div>
    <div class="d-flex justify-content-between"><span>Payé</span><span>{{ number_format($vente->montant_paye, 0, ',', ' ') }} F</span></div>
    <div class="d-flex justify-content-between text-danger"><span>Reste à payer</span><span>{{ number_format($vente->reste_a_payer, 0, ',', ' ') }} F</span></div>
</div>
<div class="card-app p-4 mt-3">
    <h5 class="mb-3">Paiements</h5>

    @if($vente->paiements->isNotEmpty())
        <table class="table table-app mb-4">
            <thead>
                <tr><th>Date</th><th>Mode</th><th>Référence</th><th class="text-end">Montant</th><th></th></tr>
            </thead>
            <tbody>
            @foreach($vente->paiements->sortBy('date_paiement') as $paiement)
                <tr>
                    <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                    <td>{{ $paiement->mode_paiement === 'mobile_money' ? 'Mobile Money' : 'Espèces' }}</td>
                    <td>{{ $paiement->reference ?: '—' }}</td>
                    <td class="text-end">{{ number_format($paiement->montant, 0, ',', ' ') }} F</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('paiements.destroy', $paiement) }}"
                              onsubmit="return confirm('Supprimer ce paiement ?')">
                            @csrf @method('DELETE')
                            <button class="icon-btn border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Aucun paiement reçu pour l'instant.</p>
    @endif

    @if($vente->reste_a_payer > 0)
        <form method="POST" action="{{ route('paiements.store', $vente) }}" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Montant</label>
                <input type="number" name="montant" min="1" step="1" value="{{ old('montant', (int) $vente->reste_a_payer) }}"
                       class="form-control @error('montant') is-invalid @enderror" required>
                @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Mode</label>
                <select name="mode_paiement" class="form-select" required>
                    <option value="mobile_money" @selected(old('mode_paiement') === 'mobile_money')>Mobile Money</option>
                    <option value="especes" @selected(old('mode_paiement') === 'especes')>Espèces</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_paiement" value="{{ old('date_paiement', now()->format('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Référence (facultatif)</label>
                <input type="text" name="reference" value="{{ old('reference') }}" class="form-control" maxlength="100">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-rose">Enregistrer le paiement</button>
            </div>
        </form>
    @endif
</div>
@endsection