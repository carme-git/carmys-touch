@extends('layouts.app')

@section('title', 'Inventaire')

@section('content')
@include('partials.alertes')

<p class="text-muted">
    Compte tes parfums et saisis la quantité réelle. Laisse vide un parfum que tu n'as pas compté :
    il ne sera pas modifié. Chaque écart sera enregistré comme une correction dans l'historique.
</p>

<form method="POST" action="{{ route('stock.inventaire.store') }}">
    @csrf
    <div class="card-app">
        <table class="table table-app mb-0">
            <thead>
                <tr><th>Parfum</th><th>Stock dans l'appli</th><th style="width: 200px;">Quantité comptée</th></tr>
            </thead>
            <tbody>
            @foreach($produits as $p)
                <tr>
                    <td>{{ $p->nom }}</td>
                    <td>{{ $p->quantite_stock }}</td>
                    <td>
                        <input type="number" name="comptes[{{ $p->id }}]" min="0" step="1"
                               value="{{ old('comptes.' . $p->id) }}"
                               class="form-control @error('comptes.' . $p->id) is-invalid @enderror">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @error('comptes.*')<div class="text-danger mt-2">{{ $message }}</div>@enderror

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-rose"
                onclick="return confirm('Enregistrer cet inventaire ? Le stock sera ajusté aux quantités comptées.')">
            Enregistrer l'inventaire
        </button>
    </div>
</form>
@endsection