@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="container py-4">
    <small class="text-uppercase text-muted fw-semibold">Admin / Pesanan</small>
    <h1 class="sr-script display-6 mb-3">Kelola pesanan</h1>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.pesanan.index') }}" class="pill {{ !$status ? 'active' : '' }}">Semua</a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'belum-bayar']) }}" class="pill {{ $status === 'belum-bayar' ? 'active' : '' }}">Belum lunas</a>
        @foreach (\App\Models\Pesanan::TAHAP as $t)
            <a href="{{ route('admin.pesanan.index', ['status' => $t]) }}" class="pill {{ $status === $t ? 'active' : '' }}">{{ $t }}</a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.pesanan.index') }}" class="row g-2 mb-3">
        @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
        <div class="col"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                                placeholder="Cari kode pesanan atau nama penerima..."></div>
        <div class="col-auto"><button class="btn btn-sr btn-sm">Cari</button></div>
    </form>

    <div class="co-section p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Pelanggan</th><th>Jadwal kirim</th><th>Total</th><th>Bayar</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td class="small fw-semibold">{{ $p->kode_pesanan }}</td>
                            <td class="small">{{ $p->nama_penerima }}<div class="text-muted">{{ $p->user->username ?? '-' }}</div></td>
                            <td class="small">{{ $p->tanggal_pengiriman->translatedFormat('j M Y') }}<div class="text-muted">{{ $p->waktu_pengiriman }}</div></td>
                            <td class="menu-price small">Rp{{ number_format($p->total_biaya, 0, ',', '.') }}</td>
                            <td class="small {{ $p->status_pembayaran === 'Lunas' ? 'text-success' : 'text-danger' }}">{{ $p->status_pembayaran }}</td>
                            <td>@include('partials.status-badge', ['status' => $p->status_pesanan])</td>
                            <td class="text-end"><a href="{{ route('admin.pesanan.show', $p) }}" class="btn btn-sr-outline btn-sm">Kelola</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">{{ $pesanan->links() }}</div>
</div>
@endsection