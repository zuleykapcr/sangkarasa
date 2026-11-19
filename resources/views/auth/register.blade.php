@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-3">
            <h1 class="sr-script h2 mb-1">Yuk, jadi teman makan.</h1>
            <p class="text-muted small">Buat akun untuk mulai memesan masakan rumahan.</p>
        </div>

        <form method="POST" action="{{ route('register.proses') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label small fw-semibold">Nama lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="username" class="form-label small fw-semibold">Nama pengguna</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                       class="form-control @error('username') is-invalid @enderror">
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="no_whatsapp" class="form-label small fw-semibold">Nomor WhatsApp <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                       class="form-control @error('no_whatsapp') is-invalid @enderror" placeholder="0812xxxxxxx">
                @error('no_whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold">Kata sandi</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label small fw-semibold">Ulangi kata sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-sr w-100">Daftar sekarang →</button>
        </form>

        <p class="text-center small text-muted mt-3 mb-0">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none sr-link">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection