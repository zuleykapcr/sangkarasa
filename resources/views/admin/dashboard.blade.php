@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <small class="text-uppercase text-muted fw-semibold">Admin / Dashboard</small>
    <h1 class="sr-script display-5">Halo, {{ auth()->user()->name }}</h1>
    <p class="text-muted">Dashboard admin akan diisi di Tahap 9 (CRUD menu dan kelola pesanan).</p>
</div>
@endsection