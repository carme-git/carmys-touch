@extends('layouts.app')

@section('title', 'Modifier la dépense')

@section('content')
<form method="POST" action="{{ route('depenses.update', $depense) }}" class="card-app p-4">
    @csrf
    @method('PUT')
    @include('depenses._form')
    <div class="mt-4">
        <a href="{{ route('depenses.index') }}" class="btn btn-light">Annuler</a>
        <button type="submit" class="btn btn-rose">Enregistrer</button>
    </div>
</form>
@endsection