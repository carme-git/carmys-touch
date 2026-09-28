@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ route('clients.index') }}" class="d-flex" style="width:280px;">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher une cliente">
    </form>
    <a href="{{ route('clients.create') }}" class="btn btn-rose"><i class="bi bi-plus-lg"></i> Nouvelle cliente</a>
</div>

<div class="card-app p-0" style="overflow:hidden;">
    <table class="table-app">
        <thead>
            <tr>
                <th>Cliente</th><th>Téléphone</th><th>Commandes</th><th>Solde impayé</th><th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($clients as $c)
            <tr>
                <td>
                    <a href="{{ route('clients.show', $c) }}" style="color:var(--texte); text-decoration:none;">
                        <strong>{{ $c->nom_complet }}</strong>
                    </a>
                </td>
                <td>{{ $c->telephone ?? '—' }}</td>
                <td>{{ $c->ventes->count() }}</td>
                <td>
                    @if($c->solde_impaye > 0)
                        <span class="badge-app badge-impaye">{{ number_format($c->solde_impaye, 0, ',', ' ') }} F</span>
                    @else
                        <span class="badge-app badge-paye">À jour</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('clients.show', $c) }}" class="icon-btn"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('clients.edit', $c) }}" class="icon-btn"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('clients.destroy', $c) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer cette cliente ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-4" style="color:var(--texte-doux);">Aucune cliente pour le moment</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection