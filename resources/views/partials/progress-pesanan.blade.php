<div class="progress-steps">
    @foreach (\App\Models\Pesanan::LABEL_TAHAP as $i => $label)
        <div class="step {{ $i < $pesanan->tahap_index ? 'done' : '' }} {{ $i === $pesanan->tahap_index ? 'now' : '' }}">
            <span class="dot"></span>
            <span class="step-label">{{ $label }}</span>
        </div>
    @endforeach
</div>
