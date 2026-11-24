<div class="card menu-card h-100">
    <img src="{{ $menu->gambar_url }}" alt="{{ $menu->nama_menu }}" class="card-img-top">
    <div class="card-body d-flex flex-column">
        <small class="text-muted">{{ $menu->kategori->nama_kategori }}</small>
        <h6 class="fw-bold mb-1">{{ $menu->nama_menu }}</h6>

        <div class="small mb-1">
            @if ($menu->ulasan_count)
                @include('partials.bintang', ['nilai' => $menu->ulasan_avg_rating])
                <strong>{{ number_format($menu->ulasan_avg_rating, 1, ',', '.') }}</strong>
                <span class="text-muted">({{ $menu->ulasan_count }} ulasan)</span>
            @else
                <span class="text-muted">Belum ada ulasan</span>
            @endif
        </div>

        <p class="small text-muted mb-2">{{ $menu->deskripsi }}</p>
        <div class="menu-price mb-3">
            Rp{{ number_format($menu->harga, 0, ',', '.') }}
            <span class="text-muted fw-normal small">/ porsi</span>
        </div>
        <a href="{{ route('menu.show', $menu) }}" class="btn btn-sr mt-auto">Pilih menu →</a>
    </div>
</div>