@extends('layouts.app')

@section('title', 'Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <nav class="small text-muted mb-2">
        <a href="{{ route('pesanan.index') }}" class="text-muted text-decoration-none">Riwayat pesanan</a> ›
        <span class="text-dark">{{ $pesanan->kode_pesanan }}</span>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="sr-script display-6 mb-0">{{ $pesanan->kode_pesanan }}</h1>
        @include('partials.status-badge', ['status' => $pesanan->status_pesanan])
    </div>

    @if ($pesanan->status_pesanan !== 'Selesai')
        <div class="co-section">
            <div class="co-title">Status pesanan</div>
            @include('partials.progress-pesanan', ['pesanan' => $pesanan])
        </div>
    @endif

    {{-- Item --}}
    <div class="co-section">
        <div class="co-title">Rincian pesanan</div>

        @foreach ($pesanan->detail as $d)
            <div class="d-flex justify-content-between small mb-2">
                <div>
                    <div class="fw-semibold">{{ $d->menu->nama_menu ?? 'Menu dihapus' }}</div>
                    <div class="text-muted">{{ $d->jumlah_porsi }} porsi · {{ $d->opsi_tambahan }}</div>
                </div>
                <div class="menu-price">Rp{{ number_format($d->subtotal, 0, ',', '.') }}</div>
            </div>
        @endforeach

        <hr>
        <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Ongkos kirim</span>
            <span>Rp{{ number_format($pesanan->ongkos_kirim, 0, ',', '.') }}</span>
        </div>
        @if ($pesanan->potongan_diskon > 0)
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Potongan voucher</span>
                <span class="text-success">−Rp{{ number_format($pesanan->potongan_diskon, 0, ',', '.') }}</span>
            </div>
        @endif
        <hr>
        <div class="d-flex justify-content-between">
            <strong>Total pembayaran</strong>
            <span class="menu-price fs-5">Rp{{ number_format($pesanan->total_biaya, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Pengiriman & pembayaran --}}
    <div class="co-section small">
        <div class="co-title">Pengiriman & pembayaran</div>
        <div class="mb-1"><strong>Penerima:</strong> {{ $pesanan->nama_penerima }}</div>
        <div class="mb-1"><strong>Alamat:</strong> {{ $pesanan->alamat_pengiriman }}, {{ $pesanan->kota }} {{ $pesanan->kode_pos }}</div>
        <div class="mb-1"><strong>Jadwal:</strong> {{ $pesanan->tanggal_pengiriman->translatedFormat('l, j F Y') }} · {{ $pesanan->waktu_pengiriman }}</div>
        @if ($pesanan->catatan_pesanan)
            <div class="mb-1"><strong>Catatan:</strong> {{ $pesanan->catatan_pesanan }}</div>
        @endif
        <div class="mb-1"><strong>Metode bayar:</strong> {{ $pesanan->metode_pembayaran }}</div>
        <div><strong>Status bayar:</strong>
            <span class="{{ $pesanan->status_pembayaran === 'Lunas' ? 'text-success' : 'text-danger' }}">
                {{ $pesanan->status_pembayaran }}
            </span>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('pesanan.index') }}" class="btn btn-sr-outline">← Kembali</a>

        @if ($pesanan->status_pembayaran !== 'Lunas')
            <a href="{{ route('pesanan.pembayaran', $pesanan) }}" class="btn btn-sr-outline">Lihat cara bayar</a>
        @endif

        @if ($pesanan->status_pesanan === 'Selesai')
            <form method="POST" action="{{ route('pesanan.ulang', $pesanan) }}">
                @csrf
                <button type="submit" class="btn btn-sr">Pesan lagi</button>
            </form>
        @else
            <a href="https://wa.me/{{ config('sangkarasa.whatsapp') }}?text={{ urlencode('Halo SangkaRasa, saya mau menanyakan pesanan ' . $pesanan->kode_pesanan) }}"
               target="_blank" rel="noopener" class="btn btn-sr">Hubungi dapur</a>
        @endif
    </div>
</div>
@endsection
