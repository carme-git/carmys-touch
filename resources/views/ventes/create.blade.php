@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<form method="POST" action="{{ route('ventes.store') }}" class="card-app p-4">
    @csrf

    <div class="row g-3 mb-2">
        <div class="col-md-4">
            <label class="form-label">Cliente enregistrée</label>
            <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                <option value="">— Aucune / nouvelle —</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->nom_complet }}</option>
                @endforeach
            </select>
            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Ou nouvelle cliente (nom)</label>
            <input type="text" name="client_nom" value="{{ old('client_nom') }}"
                   class="form-control" placeholder="Ex. Awa Koné" maxlength="100">
        </div>
        <div class="col-md-4">
            <label class="form-label">Téléphone (facultatif)</label>
            <input type="text" name="client_telephone" value="{{ old('client_telephone') }}"
                   class="form-control" maxlength="30">
        </div>
    </div>
    <div class="form-text mb-4">Laisse les deux vides pour une vente sans cliente. Si une cliente est choisie dans la liste, la saisie est ignorée.</div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Date</label>
            <input type="date" name="date_vente" value="{{ old('date_vente', now()->format('Y-m-d')) }}"
                   class="form-control @error('date_vente') is-invalid @enderror" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Livraison</label>
            <select name="mode_livraison" class="form-select" required>
                <option value="soi_meme" @selected(old('mode_livraison') === 'soi_meme')>Par moi-même</option>
                <option value="service" @selected(old('mode_livraison') === 'service')>Par un service</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Frais de livraison</label>
            <input type="number" name="frais_livraison" id="frais" min="0" step="1"
                   value="{{ old('frais_livraison', 0) }}" class="form-control">
        </div>
    </div>

    @error('lignes') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <table class="table table-app">
        <thead>
            <tr>
                <th style="width:38%">Parfum</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Remise</th>
                <th class="text-end">Sous-total</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="lignes"></tbody>
    </table>

    <button type="button" id="ajouter" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-plus"></i> Ajouter un parfum
    </button>

    <div class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="paye_maintenant" id="paye_maintenant"
                       value="1" @checked(old('paye_maintenant'))>
                <label class="form-check-label" for="paye_maintenant">Payée en totalité maintenant</label>
            </div>
        </div>
        <div class="col-md-4">
            <select name="mode_paiement_immediat" class="form-select">
                <option value="especes" @selected(old('mode_paiement_immediat') === 'especes')>Espèces</option>
                <option value="mobile_money" @selected(old('mode_paiement_immediat') === 'mobile_money')>Mobile Money</option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div class="fs-5">Total : <strong id="total">0 F</strong></div>
        <div>
            <a href="{{ route('ventes.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-rose">Enregistrer la vente</button>
        </div>
    </div>
</form>

@php
    // Les données sont préparées ici : @json ne découpe mal que les expressions contenant des virgules
    $produitsJs = $produits->map(fn ($p) => [
        'id'    => $p->id,
        'nom'   => $p->nom,
        'prix'  => $p->prix_vente,
        'stock' => $p->quantite_stock,
    ])->values();

    // array_values : après une erreur de validation, les clés de old() peuvent avoir des trous
    $lignesInitiales = array_values(old('lignes', [
        ['produit_id' => '', 'quantite' => 1, 'prix_unitaire' => '', 'remise' => 0],
    ]));
@endphp

<script>
    const produits = @json($produitsJs);
    const initiales = @json($lignesInitiales);
    const corps = document.getElementById('lignes');
    let compteur = 0;

    const fmt = n => Math.round(n).toLocaleString('fr-FR') + ' F';

    function ajouterLigne(l = {}) {
        const i = compteur++;
        const options = produits.map(p =>
            `<option value="${p.id}" data-prix="${p.prix}" ${p.id == l.produit_id ? 'selected' : ''}>${p.nom} (stock : ${p.stock})</option>`
        ).join('');

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><select name="lignes[${i}][produit_id]" class="form-select produit" required>
                <option value="">— Choisir —</option>${options}</select></td>
            <td><input type="number" name="lignes[${i}][quantite]" class="form-control quantite" min="1" value="${l.quantite ?? 1}" required></td>
            <td><input type="number" name="lignes[${i}][prix_unitaire]" class="form-control prix" min="0" step="0.01" value="${l.prix_unitaire ?? ''}" required></td>
            <td><input type="number" name="lignes[${i}][remise]" class="form-control remise" min="0" step="0.01" value="${l.remise ?? 0}"></td>
            <td class="sous-total text-end">0 F</td>
            <td><button type="button" class="icon-btn supprimer"><i class="bi bi-x-lg"></i></button></td>`;
        corps.appendChild(tr);
        recalculer();
    }

    function recalculer() {
        let total = 0;
        corps.querySelectorAll('tr').forEach(tr => {
            const q = parseFloat(tr.querySelector('.quantite').value) || 0;
            const p = parseFloat(tr.querySelector('.prix').value) || 0;
            const r = parseFloat(tr.querySelector('.remise').value) || 0;
            const st = q * p - r;
            tr.querySelector('.sous-total').textContent = fmt(st);
            total += st;
        });
        total += parseFloat(document.getElementById('frais').value) || 0;
        document.getElementById('total').textContent = fmt(total);
    }

    // Changer de parfum préremplit le prix du catalogue
    corps.addEventListener('change', e => {
        if (e.target.classList.contains('produit')) {
            const opt = e.target.selectedOptions[0];
            e.target.closest('tr').querySelector('.prix').value = opt.dataset.prix ?? '';
            recalculer();
        }
    });
    corps.addEventListener('input', recalculer);
    corps.addEventListener('click', e => {
        const bouton = e.target.closest('.supprimer');
        if (bouton && corps.children.length > 1) {
            bouton.closest('tr').remove();
            recalculer();
        }
    });
    document.getElementById('frais').addEventListener('input', recalculer);
    document.getElementById('ajouter').addEventListener('click', () => ajouterLigne());

    initiales.forEach(l => ajouterLigne(l));
</script>
@endsection