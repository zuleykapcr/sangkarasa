@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')
<div class="container py-4">
    <small class="text-uppercase text-muted fw-semibold">Akun saya / Keranjang</small>
    <h1 class="sr-script display-5 mb-4">Keranjang saya</h1>

    @if ($items->isEmpty())
        <div class="text-center text-muted py-5">
            <p class="mb-3">Keranjangmu masih kosong.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-sr">Lihat menu katering →</a>
        </div>
    @else
        <div class="row g-4">
            {{-- Daftar item --}}
            <div class="col-lg-8">
                @foreach ($items as $item)
                    <div class="cart-item mb-3">
                        <img src="{{ $item['menu']->gambar_url }}"
                             alt="{{ $item['menu']->nama_menu }}" class="cart-img">

                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $item['menu']->nama_menu }}</div>
                            <div class="small text-muted">
                                {{ $item['opsi'] }} · Rp{{ number_format($item['menu']->harga, 0, ',', '.') }} / porsi
                            </div>

                            <form method="POST" action="{{ route('keranjang.update', $item['key']) }}"
                                  class="d-flex gap-2 mt-2 align-items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="jumlah_porsi" value="{{ $item['porsi'] }}"
                                       min="1" max="100" class="form-control form-control-sm" style="width: 80px;">
                                <button type="submit" class="btn btn-sr-outline btn-sm">Ubah</button>
                            </form>
                        </div>

                        <div class="text-end">
                            <div class="menu-price mb-2">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                            <form method="POST" action="{{ route('keranjang.destroy', $item['key']) }}"
                                  onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link btn-sm text-danger p-0">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                @if ($errors->has('jumlah_porsi'))
                    <div class="text-danger small">{{ $errors->first('jumlah_porsi') }}</div>
                @endif

                <a href="{{ route('menu.index') }}" class="small sr-link text-decoration-none">← Tambah menu lain</a>
            </div>

            {{-- Ringkasan --}}
            <div class="col-lg-4">
                <div class="order-card">
                    <strong>Ringkasan</strong>
                    <hr>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Subtotal · {{ $items->sum('porsi') }} porsi</span>
                        <span>Rp{{ number_format($items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="small text-muted">Ongkos kirim dan voucher dihitung di halaman berikutnya.</div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-sr w-100 mt-3">Lanjut ke pemesanan →</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection