@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container py-4" style="max-width: 760px;">
    <small class="text-uppercase text-muted fw-semibold">Admin / Kategori</small>
    <h1 class="sr-script display-6 mb-3">Kelola kategori</h1>

    <form method="POST" action="{{ route('admin.kategori.store') }}" class="co-section">
        @csrf
        <label class="form-label small fw-semibold">Kategori baru</label>
        <div class="input-group">
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                   class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Contoh: Dessert">
            <button class="btn btn-sr" type="submit">Tambah</button>
            @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </form>

    <div class="co-section">
        @foreach ($kategori as $k)
            <div class="d-flex gap-2 align-items-center mb-2">
                <form method="POST" action="{{ route('admin.kategori.update', $k) }}" class="d-flex gap-2 flex-grow-1">
                    @csrf
                    @method('PUT')
                    <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" class="form-control form-control-sm">
                    <button class="btn btn-sr-outline btn-sm" type="submit">Simpan</button>
                </form>
                <span class="small text-muted" style="min-width: 70px;">{{ $k->menu_count }} menu</span>
                <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}"
                      onsubmit="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection