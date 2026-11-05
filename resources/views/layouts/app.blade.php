<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - GudangSmart</title>
    <link rel="stylesheet" href="{{ asset('css/gudangsmart.css') }}">
</head>
<body>
    {{-- Header --}}
    <x-navbar />

    <div class="shell">
        {{-- Navigation / Sidebar --}}
        <x-sidebar />

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
    </div>

    {{-- Footer --}}
    <footer class="footer">&copy; {{ date('Y') }} GudangSmart - Sistem Manufaktur dan Inventory Gudang</footer>
</body>
</html>
