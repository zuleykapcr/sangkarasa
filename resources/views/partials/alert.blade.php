@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success mb-0">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger mb-0">{{ session('error') }}</div>
    </div>
@endif