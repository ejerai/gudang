@php
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $online = true;
    } catch (\Throwable $e) {
        $online = false;
    }
    $user = auth()->user();
@endphp
<header class="navbar">
    <a class="brand" href="{{ route('dashboard') }}">
        <span class="brand-mark">G</span>
        GudangSmart
    </a>
    <div class="navbar-right">
        <span class="status {{ $online ? 'status-on' : 'status-off' }}">
            <i></i>{{ $online ? 'Sistem terhubung' : 'Koneksi terputus' }}
        </span>
        @if ($user)
            <span class="user">
                <strong>{{ $user->name }}</strong>
                <small>{{ $user->role === 'manager' ? 'Manager Gudang' : 'Staff Gudang' }}</small>
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
            </form>
        @endif
    </div>
</header>
