@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <small class="text-uppercase text-muted fw-semibold">Admin / Dashboard</small>
    <h1 class="sr-script display-5 mb-4">Halo, {{ auth()->user()->name }}</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="small text-muted">Total menu</div><div class="stat-num">{{ $stat['menu'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="small text-muted">Total pesanan</div><div class="stat-num">{{ $stat['pesanan'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="small text-muted">Sedang berlangsung</div><div class="stat-num">{{ $stat['berlangsung'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="small text-muted">Pendapatan (lunas)</div><div class="stat-num">Rp{{ number_format($stat['pendapatan'], 0, ',', '.') }}</div></div></div>
    </div>

    @if ($stat['perlu_cek'] > 0)
        <div class="alert-dapur mb-4">
            <strong>{{ $stat['perlu_cek'] }} pesanan</strong> belum lunas.
            <a href="{{ route('admin.pesanan.index', ['status' => 'belum-bayar']) }}" class="sr-link fw-semibold">Periksa pembayaran →</a>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="co-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="co-title mb-0">Pesanan terbaru</div>
                    <a href="{{ route('admin.pesanan.index') }}" class="small sr-link text-decoration-none">Lihat semua</a>
                </div>

                @forelse ($terbaru as $p)
                    <a href="{{ route('admin.pesanan.show', $p) }}" class="row-link">
                        <div>
                            <div class="fw-semibold small">{{ $p->kode_pesanan }}</div>
                            <div class="small text-muted">{{ $p->nama_penerima }} · Rp{{ number_format($p->total_biaya, 0, ',', '.') }}</div>
                        </div>
                        @include('partials.status-badge', ['status' => $p->status_pesanan])
                    </a>
                @empty
                    <p class="text-muted small mb-0">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-5">
            <div class="co-section">
                <div class="co-title">Jadwal pengiriman terdekat</div>

                @forelse ($jadwal as $p)
                    <div class="small mb-2">
                        <div class="fw-semibold">{{ $p->tanggal_pengiriman->translatedFormat('D, j M Y') }} · {{ $p->waktu_pengiriman }}</div>
                        <div class="text-muted">{{ $p->kode_pesanan }} · {{ $p->nama_penerima }}, {{ $p->kota }}</div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Tidak ada jadwal.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="co-section mt-2">
        <div class="co-title">Laporan penjualan 7 hari terakhir (pesanan lunas)</div>

        @if ($laporan->isEmpty())
            <p class="text-muted small mb-0">Belum ada penjualan lunas dalam 7 hari terakhir.</p>
        @else
            <table class="table table-sm mb-0">
                <thead>
                    <tr><th>Tanggal</th><th class="text-end">Jumlah pesanan</th><th class="text-end">Pendapatan</th></tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $tgl => $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, j F Y') }}</td>
                            <td class="text-end">{{ $row['jumlah'] }}</td>
                            <td class="text-end">Rp{{ number_format($row['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <td>Total</td>
                        <td class="text-end">{{ $laporan->sum('jumlah') }}</td>
                        <td class="text-end">Rp{{ number_format($laporan->sum('total'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>
@endsection