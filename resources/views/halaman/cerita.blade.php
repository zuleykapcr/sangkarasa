@extends('layouts.app')

@section('title', 'Cerita Kami')

@section('content')
<div class="container py-4" style="max-width: 860px;">
    <small class="text-uppercase text-muted fw-semibold">Tentang kami</small>
    <h1 class="sr-script display-5 mb-3">Cerita kami</h1>

    <img src="{{ asset('images/hero.jpg') }}" alt="Hidangan SangkaRasa" class="detail-banner mb-4">

    <p>SangkaRasa adalah usaha katering rumahan di Pekanbaru yang berangkat dari satu keyakinan sederhana: makanan terbaik adalah makanan yang terasa seperti dimasak di rumah sendiri.</p>
    <p>Setiap menu kami masak segar sesuai pesanan, dengan bumbu yang diracik perlahan dan bahan pilihan. Tidak ada pengawet, tidak ada jalan pintas, hanya dapur yang hangat dan tangan yang telaten.</p>
    <p>Dulu, pesanan kami catat lewat pesan singkat. Sekarang kami menyiapkan sistem pemesanan daring agar kamu bisa memilih menu, menentukan jadwal antar, dan memantau pesanan dengan lebih tenang, dari dapur kami sampai ke meja makanmu.</p>

    <div class="row g-3 mt-2">
        <div class="col-md-4"><div class="stat-card"><div class="stat-num">Segar</div><div class="small text-muted">Dimasak sesuai pesanan, tepat sebelum diantar.</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-num">Rumahan</div><div class="small text-muted">Resep keluarga dengan rasa yang akrab.</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-num">Tepat waktu</div><div class="small text-muted">Pengiriman sesuai jadwal yang kamu pilih.</div></div></div>
    </div>

    <a href="{{ route('menu.index') }}" class="btn btn-sr mt-4">Lihat menu katering →</a>
</div>
@endsection