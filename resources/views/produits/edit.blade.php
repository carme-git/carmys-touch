@extends('layouts.app')
@section('title', 'Modifier le produit')

@section('content')
<div class="card-app" style="max-width:820px;">
    <form method="POST" action="{{ route('produits.update', $produit) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('produits._form')
    </form>
</div>
@endsection