@extends('layouts.app')

@section('title', 'Nouvelle cliente')

@section('content')
@include('partials.alertes')

<div class="card-app p-4">
    <form method="POST" action="{{ route('clients.store') }}">
        @include('clients._form')
    </form>
</div>
@endsection