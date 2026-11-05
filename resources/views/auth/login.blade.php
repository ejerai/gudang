<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - GudangSmart</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/gudangsmart.css') }}">
</head>
<body>
    <div class="auth">
        <div class="auth-card">
            <div class="mark"><x-logo class="logo" /></div>
            <h1>Masuk ke GudangSmart</h1>
            <p class="lead">Gunakan akun manager atau staff gudang.</p>

            @if (session('warning'))
                <x-alert type="warning">{{ session('warning') }}</x-alert>
            @elseif (session('url.intended') && ! $errors->any())
                <x-alert type="info">Silakan login terlebih dahulu untuk melanjutkan.</x-alert>
            @endif
            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf
                <x-form-input name="email" label="Email" type="email" placeholder="nama@email.com" autocomplete="username" autofocus required />
                <x-form-input name="password" label="Password" type="password" placeholder="Masukkan password" autocomplete="current-password" required />
                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>
            <p class="auth-note">manager@gudangsmart.test | staff@gudangsmart.test Password: <code>password</code></p>
        </div>
    </div>
</body>
</html>