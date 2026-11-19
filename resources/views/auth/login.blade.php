@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-3">
            <h1 class="sr-script h2 mb-1">Senang kamu kembali.</h1>
            <p class="text-muted small">Masuk dulu, yuk. Menu masakan favoritmu sudah menunggu.</p>
        </div>

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label small fw-semibold">Nama pengguna</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                       class="form-control @error('username') is-invalid @enderror" autofocus>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold">Kata sandi</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-sr w-100">Masuk ke SangkaRasa →</button>
        </form>

        <p class="text-center small text-muted mt-3 mb-0">
            Belum jadi teman makan?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none sr-link">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection