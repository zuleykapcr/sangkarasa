@extends('layouts.app')

@section('title', 'Pembayaran ' . $pesanan->kode_pesanan)

@section('content')
<div class="container py-4" style="max-width: 760px;">
    <small class="text-uppercase text-muted fw-semibold">Pembayaran / {{ $pesanan->kode_pesanan }}</small>
    <h1 class="sr-script display-5 mb-3">Tinggal satu langkah lagi</h1>

    {{-- Total --}}
    <div class="order-card mb-4 text-center">
        <div class="small text-muted">Total pembayaran</div>
        <div class="menu-price display-6">Rp{{ number_format($pesanan->total_biaya, 0, ',', '.') }}</div>
        <div class="small text-muted">Metode: {{ $pesanan->metode_pembayaran }} · Status: {{ $pesanan->status_pembayaran }}</div>
    </div>

    {{-- Instruksi --}}
    <div class="co-section">
        @if ($pesanan->metode_pembayaran === 'QRIS')
            <div class="co-title">Bayar dengan QRIS</div>
            <div class="text-center">
                @if (file_exists(public_path('images/qris.png')))
                    <img src="{{ asset('images/qris.png') }}" alt="QRIS SangkaRasa" style="max-width: 240px;" class="mb-2">
                @else
                    <div class="qris-box mb-2">QRIS</div>
                    <div class="small text-muted">Simpan gambar QRIS usaha di <code>public/images/qris.png</code> untuk menampilkannya di sini.</div>
                @endif
                <p class="small mt-2 mb-0">Pindai dari aplikasi bank atau dompet digital, lalu bayar sesuai total di atas.</p>
            </div>
        @else
            <div class="co-title">Transfer ke salah satu rekening</div>
            <table class="table table-sm mb-2">
                @foreach (config('sangkarasa.rekening') as $bank => $noRek)
                    <tr>
                        <td class="fw-semibold">{{ $bank }}</td>
                        <td>{{ $noRek }}</td>
                    </tr>
                @endforeach
            </table>
            <div class="small text-muted">a.n. {{ config('sangkarasa.atas_nama') }}</div>
        @endif

        <div class="alert-dapur mt-3 small">
            Setelah membayar, admin akan memeriksa pembayaranmu dan memperbarui status pesanan.
        </div>
    </div>

    {{-- Ringkasan pesanan --}}
    <div class="co-section">
        <div class="co-title">Ringkasan pesanan</div>

        @foreach ($pesanan->detail as $d)
            <div class="d-flex justify-content-between small mb-1">
                <span>{{ $d->menu->nama_menu }} · {{ $d->jumlah_porsi }} porsi · {{ $d->opsi_tambahan }}</span>
                <span>Rp{{ number_format($d->subtotal, 0, ',', '.') }}</span>
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
        <div class="small">
            <strong>Dikirim ke:</strong> {{ $pesanan->nama_penerima }}, {{ $pesanan->alamat_pengiriman }},
            {{ $pesanan->kota }} {{ $pesanan->kode_pos }}<br>
            <strong>Jadwal:</strong> {{ $pesanan->tanggal_pengiriman->translatedFormat('l, j F Y') }} · {{ $pesanan->waktu_pengiriman }}
            @if ($pesanan->catatan_pesanan)
                <br><strong>Catatan:</strong> {{ $pesanan->catatan_pesanan }}
            @endif
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('menu.index') }}" class="btn btn-sr-outline">Pesan menu lain</a>
        <a href="{{ route('pesanan.index') }}" class="btn btn-sr">Lihat riwayat pesanan</a>
    </div>
</div>
@endsection
