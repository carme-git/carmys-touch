@extends('layouts.app')
@section('title', 'Nouvelle cliente')

@section('content')
<div class="card-app" style="max-width:820px;">
    <form method="POST" action="{{ route('clients.store') }}">
        @include('clients._form')
    </form>
</div>
@endsection