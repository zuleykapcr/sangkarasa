@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
        <div>
            <small class="text-uppercase text-muted fw-semibold">Admin / Menu</small>
            <h1 class="sr-script display-6 mb-0">Kelola menu</h1>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-sr">+ Tambah menu</a>
    </div>

    <form method="GET" action="{{ route('admin.menu.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                   placeholder="Cari nama menu...">
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select form-select-sm">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sr-outline btn-sm w-100">Terapkan</button></div>
    </form>

    <div class="co-section p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th style="width:80px">Gambar</th><th>Nama menu</th><th>Kategori</th><th>Harga</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($menus as $m)
                        <tr>
                            <td><img src="{{ $m->gambar_url }}" alt="{{ $m->nama_menu }}" class="thumb"></td>
                            <td>
                                <div class="fw-semibold">{{ $m->nama_menu }}</div>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($m->deskripsi, 50) }}</div>
                            </td>
                            <td>{{ $m->kategori->nama_kategori }}</td>
                            <td class="menu-price">Rp{{ number_format($m->harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.menu.edit', $m) }}" class="btn btn-sr-outline btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $m) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus menu {{ $m->nama_menu }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Menu tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">{{ $menus->links() }}</div>
</div>
@endsection