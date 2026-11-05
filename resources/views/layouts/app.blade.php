<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - GudangSmart</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/gudangsmart.css') }}">
</head>
<body>
    <div class="app">
        {{-- Navigation / Sidebar --}}
        <x-sidebar />

        <div class="main">
            {{-- Header --}}
            <x-navbar />

            {{-- Content Area --}}
            <main class="content">
                @if (session('success'))
                    <x-alert type="success">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="danger">{{ session('error') }}</x-alert>
                @endif
                @if (session('warning'))
                    <x-alert type="warning">{{ session('warning') }}</x-alert>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="footer">&copy; {{ date('Y') }} GudangSmart - Sistem Manufaktur dan Inventory Gudang</footer>
        </div>
    </div>
    <div class="scrim" data-nav-toggle></div>

    <script>
        // Buka/tutup sidebar: laci di layar kecil, sembunyikan permanen di layar besar
        (function () {
            var body = document.body, kecil = window.matchMedia('(max-width: 960px)');
            try { if (!kecil.matches && localStorage.getItem('gs-nav') === 'hidden') body.classList.add('nav-hidden'); } catch (e) {}
            document.querySelectorAll('[data-nav-toggle]').forEach(function (el) {
                el.addEventListener('click', function () {
                    if (kecil.matches) { body.classList.toggle('nav-open'); return; }
                    var sembunyi = body.classList.toggle('nav-hidden');
                    try { localStorage.setItem('gs-nav', sembunyi ? 'hidden' : 'shown'); } catch (e) {}
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
