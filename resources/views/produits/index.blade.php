@extends('layouts.app')
@section('title', 'Produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ route('produits.index') }}" class="d-flex" style="width:280px;">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un produit">
    </form>
    <a href="{{ route('produits.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouveau produit</a>
</div>

<div class="card-app p-0" style="overflow:hidden;">
    <table class="table-app">
        <thead>
            <tr>
                <th></th><th>Nom</th><th>Catégorie</th><th>Prix achat</th><th>Prix vente</th><th>Stock</th><th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($produits as $p)
            <tr>
                <td style="width:60px;">
                    @if($p->image)
                        <img src="{{ asset('storage/'.$p->image) }}" class="thumb" alt="">
                    @else
                        <div class="thumb"></div>
                    @endif
                </td>
                <td><strong>{{ $p->nom }}</strong></td>
                <td>{{ $p->categorie->nom ?? '—' }}</td>
                <td>{{ number_format($p->prix_achat, 0, ',', ' ') }} F</td>
                <td>{{ number_format($p->prix_vente, 0, ',', ' ') }} F</td>
                <td>
                    @if($p->quantite_stock <= $p->seuil_alerte)
                        <span class="badge-app badge-impaye">{{ $p->quantite_stock }} — bas</span>
                    @else
                        {{ $p->quantite_stock }}
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('produits.edit', $p) }}" class="icon-btn"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('produits.destroy', $p) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer ce produit ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4" style="color:var(--texte-doux);">Aucun produit pour le moment 🌸</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection