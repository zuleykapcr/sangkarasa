@extends('layouts.app')

@section('title', 'Ubah Menu')

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <small class="text-uppercase text-muted fw-semibold">Admin / Menu / Ubah</small>
    <h1 class="sr-script display-6 mb-3">Ubah menu</h1>

    <form method="POST" action="{{ route('admin.menu.update', $menu) }}" enctype="multipart/form-data" class="co-section">
        @csrf
        @method('PUT')
        @include('admin.menu._form')

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sr">Simpan perubahan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-sr-outline">Batal</a>
        </div>
    </form>
</div>
@endsection