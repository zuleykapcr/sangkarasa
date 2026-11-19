@extends('layouts.app')

@section('title', 'Pemesanan')

@section('content')
<div class="container py-4">
    <nav class="small text-muted mb-2">
        <a href="{{ route('menu.index') }}" class="text-muted text-decoration-none">Menu katering</a> ›
        <a href="{{ route('keranjang.index') }}" class="text-muted text-decoration-none">Keranjang</a> ›
        <span class="text-dark">Pemesanan</span>
    </nav>

    <h1 class="sr-script display-5 mb-1">Mau diantar ke mana?</h1>
    <p class="text-muted">Lengkapi detailnya, kami siapkan yang hangat untukmu.</p>

    <form method="POST" action="{{ route('checkout.store') }}" id="formCheckout">
        @csrf

        {{-- Tombol bayangan: tekan Enter di kolom isian = kirim pesanan, bukan "Pakai voucher" --}}
        <button type="submit" class="d-none" tabindex="-1" aria-hidden="true"></button>

        <div class="row g-4">
            {{-- KIRI: formulir --}}
            <div class="col-lg-7">

                {{-- 1. Alamat --}}
                <div class="co-section">
                    <div class="co-title"><span class="co-num">1</span> Alamat pengiriman</div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama penerima</label>
                        <input type="text" name="nama_penerima"
                               value="{{ old('nama_penerima', auth()->user()->name) }}"
                               class="form-control @error('nama_penerima') is-invalid @enderror">
                        @error('nama_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat lengkap</label>
                        <input type="text" name="alamat_pengiriman" value="{{ old('alamat_pengiriman') }}"
                            placeholder="Isi alamat rumah / kantor"
                            class="form-control @error('alamat_pengiriman') is-invalid @enderror">
                        <div class="form-text">Tulis alamat rumah atau kantor beserta patokan, contoh: Jl. Sangkarasa No. 12, dekat masjid.</div>
                        @error('alamat_pengiriman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kota / kabupaten</label>
                            <select name="kota" class="form-select @error('kota') is-invalid @enderror">
                                <option value="">Pilih...</option>
                                @foreach ($kota as $k)
                                    <option value="{{ $k }}" {{ old('kota', 'Pekanbaru') === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kode pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" maxlength="5"
                                   placeholder="28281"
                                   class="form-control @error('kode_pos') is-invalid @enderror">
                            @error('kode_pos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Waktu & catatan --}}
                <div class="co-section">
                    <div class="co-title"><span class="co-num">2</span> Waktu & catatan pesanan</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal pengiriman</label>
                            <input type="date" name="tanggal_pengiriman"
                                   min="{{ now()->addDay()->toDateString() }}"
                                   value="{{ old('tanggal_pengiriman', $jadwal['tanggal_pengiriman'] ?? now()->addDay()->toDateString()) }}"
                                   class="form-control @error('tanggal_pengiriman') is-invalid @enderror">
                            @error('tanggal_pengiriman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Waktu tiba</label>
                            <select name="waktu_pengiriman" class="form-select @error('waktu_pengiriman') is-invalid @enderror">
                                @foreach ($waktu as $w)
                                    <option value="{{ $w }}"
                                        {{ old('waktu_pengiriman', $jadwal['waktu_pengiriman'] ?? '11.00-12.00 WIB') === $w ? 'selected' : '' }}>
                                        {{ $w }}
                                    </option>
                                @endforeach
                            </select>
                            @error('waktu_pengiriman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <label class="form-label small fw-semibold">Catatan untuk dapur & kurir <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="catatan_pesanan" value="{{ old('catatan_pesanan') }}"
                           placeholder="Sambal dipisah, ya. Tolong taruh di pagar saja."
                           class="form-control @error('catatan_pesanan') is-invalid @enderror">
                    @error('catatan_pesanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 3. Metode pembayaran --}}
                <div class="co-section">
                    <div class="co-title"><span class="co-num">3</span> Metode pembayaran</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarQris"
                                   value="QRIS" {{ old('metode_pembayaran', 'QRIS') === 'QRIS' ? 'checked' : '' }}>
                            <label class="pay-option w-100" for="bayarQris">
                                <strong>QRIS</strong>
                                <span class="small text-muted d-block">Pindai dari aplikasi favoritmu</span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarTransfer"
                                   value="Transfer Bank" {{ old('metode_pembayaran') === 'Transfer Bank' ? 'checked' : '' }}>
                            <label class="pay-option w-100" for="bayarTransfer">
                                <strong>Transfer bank</strong>
                                <span class="small text-muted d-block">BCA, Mandiri, BNI, BRI</span>
                            </label>
                        </div>
                    </div>
                    @error('metode_pembayaran') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- KANAN: ringkasan --}}
            <div class="col-lg-5">
                <div class="order-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Pesananmu</strong>
                        <a href="{{ route('keranjang.index') }}" class="small sr-link text-decoration-none">Ubah</a>
                    </div>

                    @foreach ($items as $item)
                        <div class="d-flex justify-content-between small mb-2">
                            <div>
                                <div class="fw-semibold">{{ $item['menu']->nama_menu }}</div>
                                <div class="text-muted">{{ $item['porsi'] }} porsi · {{ $item['opsi'] }}</div>
                            </div>
                            <div class="menu-price">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach

                    <hr>

                    {{-- Voucher --}}
                    <label class="form-label small fw-semibold">Kode voucher untukmu</label>
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" name="kode_voucher" class="form-control text-uppercase"
                               value="{{ $ringkasan['voucher']['kode'] ?? '' }}"
                               placeholder="Masukkan kode"
                               {{ $ringkasan['voucher'] ? 'readonly' : '' }}>

                        @if ($ringkasan['voucher'])
                            <button type="submit" formaction="{{ route('checkout.voucher.hapus') }}"
                                    class="btn btn-outline-secondary">Lepas</button>
                        @else
                            <button type="submit" formaction="{{ route('checkout.voucher') }}"
                                    class="btn btn-sr">Pakai</button>
                        @endif
                    </div>
                    @if ($ringkasan['voucher'])
                        <div class="small text-success mb-2">
                            Hore, kamu hemat Rp{{ number_format($ringkasan['potongan'], 0, ',', '.') }}!
                        </div>
                    @endif

                    <hr>

                    {{-- Rincian biaya --}}
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Subtotal · {{ $items->sum('porsi') }} porsi</span>
                        <span>Rp{{ number_format($ringkasan['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Ongkos kirim</span>
                        <span>Rp{{ number_format($ringkasan['ongkir'], 0, ',', '.') }}</span>
                    </div>
                    @if ($ringkasan['voucher'])
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Potongan {{ $ringkasan['voucher']['nama'] }}</span>
                            <span class="text-success">−Rp{{ number_format($ringkasan['potongan'], 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total pembayaran</strong>
                        <span class="menu-price fs-5">Rp{{ number_format($ringkasan['total'], 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-sr w-100">
                        Lanjut bayar · Rp{{ number_format($ringkasan['total'], 0, ',', '.') }} →
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection