@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <small class="text-uppercase text-muted fw-semibold">Admin / Menu / Tambah</small>
    <h1 class="sr-script display-6 mb-3">Tambah menu baru</h1>

    <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="co-section">
        @csrf
        @include('admin.menu._form')

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sr">Simpan menu</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-sr-outline">Batal</a>
        </div>
    </form>
</div>
@endsection