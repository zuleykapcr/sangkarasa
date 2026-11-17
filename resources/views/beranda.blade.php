@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<section class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h1 class="sr-title display-3">Hai, sudah makan enak?</h1>
            <hr class="sr-line">
            <p class="text-muted">
                Di tengah hari yang sibuk, selalu ada rasa rumah yang menunggu.
                Masakan hangat dari dapur kami, untuk cerita di meja makanmu.
            </p>
            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('menu.index') }}" class="btn btn-sr">Temukan menu favoritmu →</a>
                <a href="#" class="btn btn-sr-outline">Kenalan dulu, yuk</a>
            </div>
            <small class="text-muted">★★★★★ 4,9/5 dari 320+ teman makan</small>
        </div>
        <div class="col-lg-6 text-center">
            <img src="{{ asset('images/hero.jpg') }}" alt="Hidangan SangkaRasa" class="img-fluid sr-hero-img">
        </div>
    </div>
</section>
@endsection
