<style>
    body {
        padding-top: 70px;
    }
    .navbar .nav-link.active {
        font-weight: bold;
        background-color: #0d6efd;
        border-radius: 5px;
        padding: 8px 12px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('even') ? 'active' : '' }}" href="{{ url('/even') }}">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('prime') ? 'active' : '' }}" href="{{ url('/prime') }}">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('multable') ? 'active' : '' }}" href="{{ url('/multable') }}">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('products') ? 'active' : '' }}" href="{{ route('products_list') }}">Products</a>
            </li>
            @auth
            <li class="nav-item">
                <a class="nav-link {{ request()->is('purchases') ? 'active' : '' }}" href="{{ route('my.purchases') }}">My Purchases</a>
            </li>
            @endauth
            @can('show_users')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users') ? 'active' : '' }}" href="{{ route('users') }}">Users</a>
            </li>
            @endcan
        </ul>

        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                    {{ auth()->user()->name }} 
                    <span class="badge bg-success">EGP {{ number_format(auth()->user()->credit, 2) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('do_logout') }}">Logout</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
