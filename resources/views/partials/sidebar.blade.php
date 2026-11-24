<aside class="sr-sidebar" id="srSidebar">
    <a class="sr-brand d-block mb-3" href="{{ route('beranda') }}">SangkaRasa</a>

    @auth
        <div class="small text-muted">Selamat datang kembali,</div>
        <div class="fw-bold">{{ auth()->user()->username }}</div>
        <div class="small text-muted mb-3">Ada rasa rumah untukmu.</div>
    @else
        <div class="small text-muted mb-3">Ada rasa rumah untukmu.</div>
    @endauth

    <nav class="nav flex-column gap-1">
        <a class="side-link {{ request()->routeIs('beranda') ? 'active' : '' }}"
           href="{{ route('beranda') }}">Beranda</a>

        <a class="side-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
           href="{{ route('menu.index') }}">Menu katering</a>

        <a class="side-link" href="#">Cerita kami</a>
        <a class="side-link" href="#">Cara pesan</a>

        @auth
            @if (auth()->user()->role === 'admin')
                <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">Dashboard admin</a>
                <a class="side-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}"
                   href="{{ route('admin.menu.index') }}">Kelola menu</a>
                <a class="side-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"
                   href="{{ route('admin.kategori.index') }}">Kelola kategori</a>
                <a class="side-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}"
                   href="{{ route('admin.pesanan.index') }}">Kelola pesanan</a>
            @else
                <a class="side-link d-flex justify-content-between align-items-center {{ request()->routeIs('keranjang.*', 'checkout.*') ? 'active' : '' }}"
                   href="{{ route('keranjang.index') }}">
                    Keranjang saya
                    @if (\App\Support\Keranjang::count() > 0)
                        <span class="badge rounded-pill badge-cart">{{ \App\Support\Keranjang::count() }}</span>
                    @endif
                </a>

                <a class="side-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}"
                   href="{{ route('pesanan.index') }}">Riwayat pesanan</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="side-link w-100 text-start border-0 bg-transparent">Keluar</button>
            </form>
        @else
            <a class="side-link {{ request()->routeIs('login') ? 'active' : '' }}"
               href="{{ route('login') }}">Masuk</a>
            <a class="side-link {{ request()->routeIs('register') ? 'active' : '' }}"
               href="{{ route('register') }}">Daftar</a>
        @endauth
    </nav>
</aside>