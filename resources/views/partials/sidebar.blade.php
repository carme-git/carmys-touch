<aside class="sidebar">
    <div class="sidebar-brand">Carmy's Touch</div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Tableau de bord
        </a>
        <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> Produits
        </a>
        <a href="{{ route('ventes.index') }}" class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i> Ventes
        </a>
        <a href="{{ route('achats.index') }}" class="nav-link {{ request()->routeIs('achats.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Achats
        </a>
        <a href="{{ route('stock.index') }}" class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i> Stock
        </a>
        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Clientes
        </a>
        <a href="{{ route('fournisseurs.index') }}" class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> Fournisseurs
        </a>
        <a href="{{ route('depenses.index') }}" class="nav-link {{ request()->routeIs('depenses.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Dépenses
        </a>
    </nav>
</aside>