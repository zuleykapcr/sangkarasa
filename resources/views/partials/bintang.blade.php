@php $penuh = (int) round($nilai); @endphp
<span class="stars" aria-label="Rating {{ number_format($nilai, 1, ',', '.') }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $penuh ? 'on' : '' }}">★</span>
    @endfor
</span>