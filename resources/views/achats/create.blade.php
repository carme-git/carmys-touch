@extends('layouts.app')

@section('title', 'Nouvel achat')

@section('content')
<form method="POST" action="{{ route('achats.store') }}" class="card-app p-4">
    @csrf

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Type d'achat</label>
            <select name="type" id="type" class="form-select" required>
                <option value="stock_avance" @selected(old('type') === 'stock_avance')>Stock d'avance</option>
                <option value="commande_client" @selected(old('type') === 'commande_client')>Sur commande d'une cliente</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Fournisseur (facultatif)</label>
            <select name="fournisseur_id" class="form-select">
                <option value="">— Marché / non précisé —</option>
                @foreach($fournisseurs as $f)
                    <option value="{{ $f->id }}" @selected(old('fournisseur_id') == $f->id)>{{ $f->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date_achat" value="{{ old('date_achat', now()->format('Y-m-d')) }}"
                   class="form-control @error('date_achat') is-invalid @enderror" required>
        </div>
        <div class="col-md-3" id="bloc-vente">
            <label class="form-label">Vente concernée (facultatif)</label>
            <select name="vente_id" class="form-select">
                <option value="">— Aucune —</option>
                @foreach($ventes as $v)
                    <option value="{{ $v->id }}" @selected(old('vente_id') == $v->id)>
                        #{{ $v->id }} · {{ $v->client->nom_complet ?? 'Sans cliente' }} · {{ $v->date_vente->format('d/m') }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @error('lignes') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <table class="table table-app">
        <thead>
            <tr>
                <th style="width:42%">Parfum</th>
                <th>Qté</th>
                <th>Prix d'achat unitaire</th>
                <th class="text-end">Sous-total</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="lignes"></tbody>
    </table>

    <button type="button" id="ajouter" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-plus"></i> Ajouter un parfum
    </button>

    <div class="d-flex justify-content-between align-items-center">
        <div class="fs-5">Total : <strong id="total">0 F</strong></div>
        <div>
            <a href="{{ route('achats.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-rose">Enregistrer l'achat</button>
        </div>
    </div>
</form>

@php
    $produitsJs = $produits->map(fn ($p) => [
        'id'    => $p->id,
        'nom'   => $p->nom,
        'prix'  => $p->prix_achat,
        'stock' => $p->quantite_stock,
    ])->values();

    $lignesInitiales = array_values(old('lignes', [
        ['produit_id' => '', 'quantite' => 1, 'prix_unitaire' => ''],
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
            tr.querySelector('.sous-total').textContent = fmt(q * p);
            total += q * p;
        });
        document.getElementById('total').textContent = fmt(total);
    }

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
    document.getElementById('ajouter').addEventListener('click', () => ajouterLigne());

    // Le choix de la vente n'apparaît que pour un achat sur commande
    const type = document.getElementById('type');
    const blocVente = document.getElementById('bloc-vente');
    const basculer = () => { blocVente.style.display = type.value === 'commande_client' ? '' : 'none'; };
    type.addEventListener('change', basculer);
    basculer();

    initiales.forEach(l => ajouterLigne(l));
</script>
@endsection