@extends('layouts.app')

@section('title', 'Modifier le fournisseur')

@section('content')
<form method="POST" action="{{ route('fournisseurs.update', $fournisseur) }}" class="card-app p-4">
    @csrf
    @method('PUT')
    @include('fournisseurs._form')
    <div class="mt-4">
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-light">Annuler</a>
        <button type="submit" class="btn btn-rose">Enregistrer</button>
    </div>
</form>
@endsection