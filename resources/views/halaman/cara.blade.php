@extends('layouts.app')

@section('title', 'Cara Pesan')

@section('content')
<div class="container py-4" style="max-width: 860px;">
    <small class="text-uppercase text-muted fw-semibold">Panduan</small>
    <h1 class="sr-script display-5 mb-4">Cara pesan</h1>

    @php
        $langkah = [
            ['Buat akun atau masuk', 'Daftar dengan nama pengguna dan kata sandi, atau masuk jika sudah punya akun.'],
            ['Pilih menu', 'Telusuri katalog, cari atau saring berdasarkan kategori, lalu buka detail menu favoritmu.'],
            ['Tentukan porsi dan jadwal', 'Pilih opsi sambal, jumlah porsi, tanggal, dan jam tiba. Pesan minimal H-1 pukul 17.00 WIB.'],
            ['Isi alamat dan bayar', 'Lengkapi alamat rumah atau kantor, pakai kode voucher jika ada, lalu bayar lewat QRIS atau transfer bank.'],
            ['Pantau pesananmu', 'Lihat status di Riwayat pesanan: dikonfirmasi, sedang dimasak, dalam pengiriman, hingga tiba di rumah.'],
            ['Beri ulasan', 'Setelah pesanan selesai, tulis bintang dan komentar di halaman menu.'],
        ];
    @endphp

    @foreach ($langkah as $i => [$judul, $isi])
        <div class="co-section d-flex gap-3 align-items-start">
            <span class="co-num flex-shrink-0" style="width:30px;height:30px;font-size:.9rem;">{{ $i + 1 }}</span>
            <div>
                <div class="fw-semibold">{{ $judul }}</div>
                <div class="small text-muted">{{ $isi }}</div>
            </div>
        </div>
    @endforeach

    <a href="{{ route('menu.index') }}" class="btn btn-sr mt-2">Mulai pesan →</a>
</div>
@endsection