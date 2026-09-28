@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="auth-carte">
    <h1 class="auth-marque">Carmy's Touch</h1>
    <p class="auth-devise">De belles senteurs, selon votre budget et qui vous respectent.</p>

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-rose w-100">Se connecter</button>
    </form>
</div>
@endsection