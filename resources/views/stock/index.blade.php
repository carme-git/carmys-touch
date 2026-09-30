@extends('layouts.app')

@section('title', 'Stock')

@section('content')
@include('partials.alertes')

<div class="d-flex justify-content-between align-items-center mb-3 gap-2">
    <form method="GET" action="{{ route('stock.index') }}" class="d-flex gap-2">
        <select name="produit_id" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les parfums</option>
            @foreach($produits as $p)
                <option value="{{ $p->id }}" @selected(request('produit_id') == $p->id)>{{ $p->nom }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('stock.inventaire') }}" class="btn btn-rose text-nowrap">
        <i class="bi bi-clipboard-check"></i> Faire un inventaire
    </a>
</div>

<div class="card-app">
    <table class="table table-app mb-0">
        <thead>
            <tr><th>Date</th><th>Parfum</th><th>Type</th><th class="text-end">Quantité</th><th>Origine</th></tr>
        </thead>
        <tbody>
        @forelse($mouvements as $m)
            @php
                $q = $m->quantite;
                $signe = match ($m->type) {
                    'entree'     => '+' . $q,
                    'sortie'     => '−' . $q,
                    default      => ($q > 0 ? '+' : '') . $q,
                };
                $badge = ['entree' => 'badge-paye', 'sortie' => 'badge-impaye', 'correction' => 'badge-partiel'][$m->type] ?? '';
                $etiquette = ['entree' => 'Entrée', 'sortie' => 'Sortie', 'correction' => 'Correction'][$m->type] ?? $m->type;
            @endphp
            <tr>
                <td>{{ $m->date_mouvement->format('d/m/Y H:i') }}</td>
                <td>{{ $m->produit->nom ?? '—' }}</td>
                <td><span class="badge-app {{ $badge }}">{{ $etiquette }}</span></td>
                <td class="text-end fw-semibold">{{ $signe }}</td>
                <td>
                    @if($m->vente_id)
                        <a href="{{ route('ventes.show', $m->vente_id) }}" class="text-decoration-none">
                            Vente #{{ $m->vente_id }}{{ $m->type === 'entree' ? ' (annulation)' : '' }}
                        </a>
                    @elseif($m->achat_id)
                        <a href="{{ route('achats.show', $m->achat_id) }}" class="text-decoration-none">Achat #{{ $m->achat_id }}</a>
                    @else
                        Inventaire / stock de départ
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun mouvement enregistré.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $mouvements->links('pagination::bootstrap-5') }}</div>
@endsection