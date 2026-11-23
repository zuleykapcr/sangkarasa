<div class="history-card mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div class="small fw-semibold">
            {{ $pesanan->kode_pesanan }}
            <span class="text-muted fw-normal">· {{ $pesanan->created_at->translatedFormat('j F Y') }}</span>
        </div>
        @include('partials.status-badge', ['status' => $pesanan->status_pesanan])
    </div>

    <div class="d-flex justify-content-between gap-3">
        <div>
            @foreach ($pesanan->detail as $d)
                <div class="fw-semibold">{{ $d->menu->nama_menu ?? 'Menu dihapus' }}</div>
                <div class="small text-muted mb-1">
                    {{ $d->jumlah_porsi }} porsi · {{ $d->opsi_tambahan }}
                </div>
            @endforeach

            <div class="small text-muted mt-1">
                Dikirim {{ $pesanan->tanggal_pengiriman->translatedFormat('l, j F Y') }} · {{ $pesanan->waktu_pengiriman }}
            </div>
        </div>

        <div class="text-end">
            <div class="small text-muted">Total pembayaran</div>
            <div class="menu-price fs-5">Rp{{ number_format($pesanan->total_biaya, 0, ',', '.') }}</div>
            <div class="small text-muted">
                {{ $pesanan->metode_pembayaran }} ·
                <span class="{{ $pesanan->status_pembayaran === 'Lunas' ? 'text-success' : 'text-danger' }}">
                    {{ $pesanan->status_pembayaran }}
                </span>
            </div>
        </div>
    </div>

    @if ($pesanan->status_pesanan !== 'Selesai')
        @include('partials.progress-pesanan', ['pesanan' => $pesanan])
    @endif

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <span class="small text-muted">
            {{ $pesanan->status_pesanan === 'Selesai' ? 'Selamat menikmati.' : 'Dapur sedang menyiapkan pesananmu.' }}
        </span>

        <div class="d-flex gap-2">
            @if ($pesanan->status_pembayaran !== 'Lunas')
                <a href="{{ route('pesanan.pembayaran', $pesanan) }}" class="btn btn-sr-outline btn-sm">Bayar</a>
            @endif

            <a href="{{ route('pesanan.show', $pesanan) }}" class="btn btn-sr-outline btn-sm">Lihat detail</a>

            @if ($pesanan->status_pesanan === 'Selesai')
                <form method="POST" action="{{ route('pesanan.ulang', $pesanan) }}">
                    @csrf
                    <button type="submit" class="btn btn-sr btn-sm">Pesan lagi</button>
                </form>
            @else
                <a href="https://wa.me/{{ config('sangkarasa.whatsapp') }}?text={{ urlencode('Halo SangkaRasa, saya mau menanyakan pesanan ' . $pesanan->kode_pesanan) }}"
                   target="_blank" rel="noopener" class="btn btn-sr btn-sm">Hubungi dapur</a>
            @endif
        </div>
    </div>
</div>
