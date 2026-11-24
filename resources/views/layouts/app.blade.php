<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catering Rumahan') | SangkaRasa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="app-shell">
        @include('partials.sidebar')

        <div class="app-content">
            {{-- Tombol buka sidebar (layar kecil) --}}
            <button class="btn btn-sr-outline btn-sm m-3 d-md-none" type="button" id="btnSidebar">☰ Menu</button>

            @include('partials.alert')

            <main>
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btnSidebar')?.addEventListener('click', () => {
            document.getElementById('srSidebar').classList.toggle('open');
        });
    </script>
    @stack('scripts')
</body>
</html>