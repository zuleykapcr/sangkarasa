@php
    $kelas = match ($status) {
        'Selesai'          => 'badge-status badge-selesai',
        'Dalam Pengiriman' => 'badge-status badge-kirim',
        'Sedang Dimasak'   => 'badge-status badge-masak',
        default            => 'badge-status badge-tunggu',
    };

    $teks = match ($status) {
        'Menunggu' => 'Menunggu konfirmasi',
        default    => $status,
    };
@endphp

<span class="{{ $kelas }}">• {{ $teks }}</span>
