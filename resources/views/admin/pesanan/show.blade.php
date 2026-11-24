@extends('layouts.app')

@section('title', 'Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <nav class="small text-muted mb-2">
        <a href="{{ route('admin.pesanan.index') }}" class="text-muted text-decoration-none">Kelola pesanan</a> ›
        <span class="text-dark">{{ $pesanan->kode_pesanan }}</span>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="sr-script display-6 mb-0">{{ $pesanan->kode_pesanan }}</h1>
        @include('partials.status-badge', ['status' => $pesanan->status_pesanan])
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
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
                <div class="d-flex justify-content-between small mb-1"><span class="text-muted">Ongkos kirim</span><span>Rp{{ number_format($pesanan->ongkos_kirim, 0, ',', '.') }}</span></div>
                @if ($pesanan->potongan_diskon > 0)
                    <div class="d-flex justify-content-between small mb-1"><span class="text-muted">Potongan voucher</span><span class="text-success">−Rp{{ number_format($pesanan->potongan_diskon, 0, ',', '.') }}</span></div>
                @endif
                <hr>
                <div class="d-flex justify-content-between"><strong>Total</strong><span class="menu-price fs-5">Rp{{ number_format($pesanan->total_biaya, 0, ',', '.') }}</span></div>
            </div>

            <div class="co-section small">
                <div class="co-title">Pengiriman</div>
                <div class="mb-1"><strong>Penerima:</strong> {{ $pesanan->nama_penerima }} ({{ $pesanan->user->no_whatsapp ?? '-' }})</div>
                <div class="mb-1"><strong>Alamat:</strong> {{ $pesanan->alamat_pengiriman }}, {{ $pesanan->kota }} {{ $pesanan->kode_pos }}</div>
                <div class="mb-1"><strong>Jadwal:</strong> {{ $pesanan->tanggal_pengiriman->translatedFormat('l, j F Y') }} · {{ $pesanan->waktu_pengiriman }}</div>
                <div class="mb-1"><strong>Catatan pelanggan:</strong> {{ $pesanan->catatan_pesanan ?: '-' }}</div>
                <div><strong>Metode bayar:</strong> {{ $pesanan->metode_pembayaran }}</div>
            </div>
        </div>

        <div class="col-lg-5">
            <form method="POST" action="{{ route('admin.pesanan.update', $pesanan) }}" class="order-card">
                @csrf
                @method('PATCH')
                <strong>Ubah status</strong>
                <hr>

                <label class="form-label small fw-semibold">Status pembayaran</label>
                <select name="status_pembayaran" class="form-select mb-3">
                    @foreach (['Belum dibayar', 'Lunas'] as $s)
                        <option value="{{ $s }}" {{ $pesanan->status_pembayaran === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>

                <label class="form-label small fw-semibold">Status pesanan</label>
                <select name="status_pesanan" class="form-select mb-3">
                    @foreach (\App\Models\Pesanan::TAHAP as $s)
                        <option value="{{ $s }}" {{ $pesanan->status_pesanan === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-sr w-100">Simpan status</button>
            </form>

            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sr-outline w-100 mt-2">← Kembali</a>
        </div>
    </div>
</div>
@endsection