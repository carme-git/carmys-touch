@extends('layouts.app')
@section('title', 'Nouveau produit')

@section('content')
<div class="card-app" style="max-width:820px;">
    <form method="POST" action="{{ route('produits.store') }}" enctype="multipart/form-data">
        @include('produits._form')
    </form>
</div>
@endsection