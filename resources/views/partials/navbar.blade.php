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

            {{-- Tombol Masuk akan diarahkan ke route login di tahap autentikasi --}}
            <a href="#" class="btn btn-sr-outline btn-sm">Masuk</a>
        </div>
    </div>
</nav>