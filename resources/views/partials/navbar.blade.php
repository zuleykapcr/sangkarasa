<nav class="navbar navbar-expand-lg sr-navbar">
    <div class="container">
        <a class="navbar-brand sr-brand" href="{{ route('beranda') }}">SangkaRasa</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNav">
            <ul class="navbar-nav mx-auto gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}"
                       href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
                       href="{{ route('menu.index') }}">Menu katering</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Cerita kami</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cara pesan</a></li>
            </ul>

            @guest
                <a href="{{ route('login') }}" class="btn btn-sr-outline btn-sm">Masuk</a>
            @endguest

           @auth
                @if (auth()->user()->role !== 'admin')
                    <a href="{{ route('keranjang.index') }}" class="btn btn-sm position-relative me-2">
                        Keranjang
                        @if (\App\Support\Keranjang::count() > 0)
                            <span class="badge rounded-pill badge-cart">{{ \App\Support\Keranjang::count() }}</span>
                        @endif
                    </a>
                @endif
                <div class="dropdown">
                    <a class="btn btn-sr-outline btn-sm dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->name }}</span></li>
                        <li><hr class="dropdown-divider"></li>

                        @if (auth()->user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard admin</a></li>
                        @else
                            <li><a class="dropdown-item" href="#">Riwayat pesanan</a></li>
                        @endif

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>