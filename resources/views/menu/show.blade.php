@extends('layouts.app')

@section('title', $menu->nama_menu)

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav class="small text-muted mb-3">
        <a href="{{ route('beranda') }}" class="text-muted text-decoration-none">Beranda</a> ›
        <a href="{{ route('menu.index') }}" class="text-muted text-decoration-none">Menu katering</a> ›
        <span class="text-dark">{{ $menu->nama_menu }}</span>
    </nav>

    {{-- Banner --}}
    <img src="{{ $menu->gambar_url }}"
         alt="{{ $menu->nama_menu }}" class="detail-banner mb-4">

    <div class="row g-4">
        {{-- KIRI: informasi menu --}}
        <div class="col-lg-8">
            <span class="badge badge-sr mb-2">{{ $menu->kategori->nama_kategori }}</span>
            <h1 class="sr-script display-5 mb-1">{{ $menu->nama_menu }}</h1>

            {{-- Rating masih statis. Diganti data asli di Tahap 10 (Ulasan & Rating) --}}
            <div class="small text-muted mb-3">
                @if ($menu->ulasan_count)
                    @include('partials.bintang', ['nilai' => $menu->ulasan_avg_rating])
                    <strong>{{ number_format($menu->ulasan_avg_rating, 1, ',', '.') }}</strong>
                    · {{ $menu->ulasan_count }} ulasan
                @else
                    Belum ada ulasan
                @endif
            </div>

            <p>{{ $menu->deskripsi }}</p>

            <div class="alert-dapur mt-4">
                <strong>Catatan dari dapur</strong><br>
                <span class="small">
                    Semua menu dimasak segar sesuai pesanan. Beri tahu kami lewat kolom
                    catatan jika kamu punya alergi makanan.
                </span>
            </div>
        </div>

        {{-- KANAN: form pesan --}}
        <div class="col-lg-4">
            <form method="POST" action="{{ route('menu.pesan', $menu) }}" class="order-card" id="formPesan">
                @csrf

                <div class="text-muted small">Satu porsi, lengkap dengan nasi</div>
                <div class="menu-price fs-5 mb-3">
                    Rp{{ number_format($menu->harga, 0, ',', '.') }}
                    <span class="text-muted fw-normal small">/ porsi</span>
                </div>

                {{-- Pilihan sambal --}}
                <label class="fw-semibold small mb-1">Pilihan sambal</label>
                <div class="d-flex gap-2 mb-3">
                    @foreach (['Sambal terpisah', 'Tanpa sambal'] as $opsi)
                        <input type="radio" class="btn-check" name="opsi_tambahan"
                               id="opsi{{ $loop->index }}" value="{{ $opsi }}"
                               {{ old('opsi_tambahan', 'Sambal terpisah') === $opsi ? 'checked' : '' }}>
                        <label class="pill" for="opsi{{ $loop->index }}">{{ $opsi }}</label>
                    @endforeach
                </div>
                @error('opsi_tambahan') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                {{-- Jumlah porsi --}}
                <label class="fw-semibold small mb-1">Jumlah porsi</label>
                <div class="input-group input-group-sm mb-1" style="max-width: 140px;">
                    <button type="button" class="btn btn-outline-secondary" id="btnKurang">−</button>
                    <input type="number" name="jumlah_porsi" id="jumlahPorsi"
                           class="form-control text-center" min="1" max="100"
                           value="{{ old('jumlah_porsi', 1) }}">
                    <button type="button" class="btn btn-outline-secondary" id="btnTambah">+</button>
                </div>
                @error('jumlah_porsi') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                {{-- Jadwal --}}
                <label class="fw-semibold small mt-3 mb-1">Tanggal pengiriman</label>
                <input type="date" name="tanggal_pengiriman" class="form-control form-control-sm"
                       min="{{ now()->addDay()->toDateString() }}"
                       value="{{ old('tanggal_pengiriman', now()->addDay()->toDateString()) }}">
                @error('tanggal_pengiriman') <div class="text-danger small">{{ $message }}</div> @enderror

                <label class="fw-semibold small mt-3 mb-1">Waktu tiba</label>
                <select name="waktu_pengiriman" class="form-select form-select-sm">
                    @foreach (['08.00-09.00 WIB', '11.00-12.00 WIB', '17.00-18.00 WIB'] as $waktu)
                        <option value="{{ $waktu }}" {{ old('waktu_pengiriman', '11.00-12.00 WIB') === $waktu ? 'selected' : '' }}>
                            {{ $waktu }}
                        </option>
                    @endforeach
                </select>
                @error('waktu_pengiriman') <div class="text-danger small">{{ $message }}</div> @enderror

                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Subtotal · <span id="infoPorsi">1</span> porsi</span>
                    <span class="menu-price" id="subtotal">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn btn-sr w-100">Pesan sekarang →</button>
                <div class="text-center text-muted small mt-2">Pesan minimal H-1 pukul 17.00 WIB.</div>
            </form>
        </div>
    </div>

        {{-- Ulasan --}}
    <hr class="my-5" id="ulasan">
    <h2 class="sr-script h3 mb-3">Ulasan pelanggan</h2>

    <div class="row g-4">
        <div class="col-lg-7">
            @forelse ($ulasan as $u)
                <div class="review-item">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold small">{{ $u->user->name }}</div>
                            @include('partials.bintang', ['nilai' => $u->rating])
                            <span class="small text-muted ms-1">{{ $u->tanggal->translatedFormat('j F Y') }}</span>
                        </div>

                        @auth
                            @if (auth()->id() === $u->user_id || auth()->user()->role === 'admin')
                                <form method="POST" action="{{ route('ulasan.destroy', $u) }}"
                                      onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0">Hapus</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    @if ($u->komentar)
                        <p class="mb-0 mt-2">{{ $u->komentar }}</p>
                    @endif
                </div>
            @empty
                <p class="text-muted">Belum ada ulasan untuk menu ini.</p>
            @endforelse

            <div class="d-flex justify-content-center mt-3">{{ $ulasan->links() }}</div>
        </div>

        <div class="col-lg-5">
            <div class="order-card">
                @guest
                    <strong>Tulis ulasan</strong>
                    <p class="small text-muted mt-2 mb-3">Masuk dulu untuk menulis ulasan.</p>
                    <a href="{{ route('login') }}" class="btn btn-sr w-100">Masuk</a>
                @else
                    @if ($bisaUlas)
                        <strong>{{ $ulasanSaya ? 'Ubah ulasanmu' : 'Tulis ulasan' }}</strong>

                        <form method="POST" action="{{ route('ulasan.store', $menu) }}" class="mt-3">
                            @csrf

                            <label class="form-label small fw-semibold">Rating</label>
                            <div class="star-input mb-1">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="bintang{{ $i }}" value="{{ $i }}"
                                           {{ (int) old('rating', $ulasanSaya->rating ?? 0) === $i ? 'checked' : '' }}>
                                    <label for="bintang{{ $i }}" title="{{ $i }} bintang">★</label>
                                @endfor
                            </div>
                            @error('rating') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                            <label class="form-label small fw-semibold mt-2">Komentar <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea name="komentar" rows="3" maxlength="500"
                                      class="form-control @error('komentar') is-invalid @enderror"
                                      placeholder="Bagaimana rasanya?">{{ old('komentar', $ulasanSaya->komentar ?? '') }}</textarea>
                            @error('komentar') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <button type="submit" class="btn btn-sr w-100 mt-3">
                                {{ $ulasanSaya ? 'Simpan perubahan' : 'Kirim ulasan' }}
                            </button>
                        </form>
                    @elseif (auth()->user()->role === 'admin')
                        <strong>Tulis ulasan</strong>
                        <p class="small text-muted mt-2 mb-0">Akun admin tidak dapat menulis ulasan, tetapi dapat menghapus ulasan yang tidak pantas.</p>
                    @else
                        <strong>Tulis ulasan</strong>
                        <p class="small text-muted mt-2 mb-0">Ulasan bisa ditulis setelah pesanan menu ini berstatus Selesai.</p>
                    @endif
                @endguest
            </div>
        </div>
    </div>
    
    {{-- Menu lain --}}
    @if ($menuLain->count())
        <hr class="my-5">
        <h2 class="sr-script h3 mb-3">Menu lain yang mungkin kamu suka</h2>
        <div class="row g-4">
            @foreach ($menuLain as $item)
                <div class="col-md-6 col-lg-4">
                    @include('partials.menu-card', ['menu' => $item])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const harga   = {{ (int) $menu->harga }};
    const input   = document.getElementById('jumlahPorsi');
    const subtotal = document.getElementById('subtotal');
    const info    = document.getElementById('infoPorsi');

    function hitung() {
        let porsi = Math.max(1, parseInt(input.value) || 1);
        input.value = porsi;
        info.textContent = porsi;
        subtotal.textContent = 'Rp' + (harga * porsi).toLocaleString('id-ID');
    }

    document.getElementById('btnKurang').addEventListener('click', () => { input.value = (parseInt(input.value) || 1) - 1; hitung(); });
    document.getElementById('btnTambah').addEventListener('click', () => { input.value = (parseInt(input.value) || 1) + 1; hitung(); });
    input.addEventListener('input', hitung);
    hitung();
</script>
@endpush