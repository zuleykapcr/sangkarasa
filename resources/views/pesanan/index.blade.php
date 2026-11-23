@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <small class="text-uppercase text-muted fw-semibold">Akun saya / Riwayat pesanan</small>
    <h1 class="sr-script display-5 mb-3">Setiap pesanan, ada ceritanya.</h1>

    {{-- Filter --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('pesanan.index') }}"
           class="pill {{ !$filter ? 'active' : '' }}">Semua pesanan {{ $jumlah['semua'] }}</a>
        <a href="{{ route('pesanan.index', ['status' => 'berlangsung']) }}"
           class="pill {{ $filter === 'berlangsung' ? 'active' : '' }}">Berlangsung {{ $jumlah['berlangsung'] }}</a>
        <a href="{{ route('pesanan.index', ['status' => 'selesai']) }}"
           class="pill {{ $filter === 'selesai' ? 'active' : '' }}">Selesai {{ $jumlah['selesai'] }}</a>
    </div>

    {{-- Daftar --}}
    @forelse ($pesanan as $p)
        @include('partials.pesanan-card', ['pesanan' => $p])
    @empty
        <div class="text-center text-muted py-5">
            <p class="mb-3">Belum ada pesanan di sini.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-sr">Lihat menu katering →</a>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-3">
        {{ $pesanan->links() }}
    </div>

    @if ($pesanan->count() > 0 && !$pesanan->hasMorePages())
        <p class="text-center text-muted small mt-4">Semua cerita rasamu sudah ditampilkan.</p>
    @endif
</div>
@endsection
