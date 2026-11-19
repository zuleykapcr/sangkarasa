@extends('layouts.app')

@section('title', 'Menu Katering')

@section('content')
<div class="container py-4">
    <small class="text-uppercase text-muted fw-semibold">Menu katering / Pilihan harian</small>
    <h1 class="sr-script display-5 mb-1">Hari ini, ingin rasa apa?</h1>
    <p class="text-muted">Pilih masakan rumahan favoritmu. Kami siapkan segar, tepat sebelum diantar.</p>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('menu.index') }}" class="row g-2 mb-3">
        @if (request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div class="col">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari rasa yang kamu inginkan..">
        </div>
        <div class="col-auto">
            <button class="btn btn-sr">Cari</button>
        </div>
    </form>

    {{-- FILTER KATEGORI --}}
    <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
        <a href="{{ route('menu.index', ['search' => request('search')]) }}"
           class="pill {{ !request('kategori') ? 'active' : '' }}">Semua menu</a>

        @foreach ($kategori as $k)
            <a href="{{ route('menu.index', ['kategori' => $k->id, 'search' => request('search')]) }}"
               class="pill {{ request('kategori') == $k->id ? 'active' : '' }}">
                {{ $k->nama_kategori }}
            </a>
        @endforeach

        <span class="ms-auto text-muted small">{{ $menus->total() }} menu untukmu</span>
    </div>

    {{-- DAFTAR MENU --}}
    <div class="row g-4">
        @forelse ($menus as $menu)
            <div class="col-md-6 col-lg-4">
                @include('partials.menu-card', ['menu' => $menu])
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                Menu yang kamu cari belum tersedia.
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $menus->links() }}
    </div>
</div>
@endsection