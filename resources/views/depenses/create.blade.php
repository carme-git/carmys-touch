@extends('layouts.app')

@section('title', 'Nouvelle dépense')

@section('content')
<form method="POST" action="{{ route('depenses.store') }}" class="card-app p-4">
    @csrf
    @include('depenses._form')
    <div class="mt-4">
        <a href="{{ route('depenses.index') }}" class="btn btn-light">Annuler</a>
        <button type="submit" class="btn btn-rose">Enregistrer</button>
    </div>
</form>
@endsection