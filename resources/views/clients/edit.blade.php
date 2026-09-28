@extends('layouts.app')
@section('title', 'Modifier la cliente')

@section('content')
<div class="card-app" style="max-width:820px;">
    <form method="POST" action="{{ route('clients.update', $client) }}">
        @method('PUT')
        @include('clients._form')
    </form>
</div>
@endsection