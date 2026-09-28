{{-- resources/views/partials/topbar.blade.php --}}
<header class="topbar">
    <span class="topbar-title">@yield('title', 'Tableau de bord')</span>
    <div class="topbar-user">
        <i class="bi bi-person-circle"></i>
        {{ auth()->user()->nom_complet }}
        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-link btn-sm text-decoration-none" style="color: var(--texte-doux);">Déconnexion</button>
        </form>
    </div>
</header>