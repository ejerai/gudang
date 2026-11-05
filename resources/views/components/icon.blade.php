@props(['name'])
<svg {{ $attributes->class(['icon']) }} viewBox="0 0 24 24" aria-hidden="true">
    @switch($name)
        @case('dashboard')<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>@break
        @case('box')<path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/>@break
        @case('swap')<path d="M7 4L3 8l4 4M3 8h14M17 20l4-4-4-4M21 16H7"/>@break
        @case('truck')<path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/>@break
        @case('tag')<path d="M3 12V4h8l10 10-8 8L3 12z"/><circle cx="7.5" cy="8.5" r="1"/>@break
        @case('chart')<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>@break
        @case('trash')<path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/>@break
        @case('search')<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>@break
        @case('bell')<path d="M6 16v-5a6 6 0 1112 0v5l2 2H4l2-2zM10 21h4"/>@break
        @case('panel')<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 4v16"/>@break
        @case('logout')<path d="M9 4H5v16h4M16 8l4 4-4 4M20 12H9"/>@break
        @case('menu')<path d="M4 6h16M4 12h16M4 18h16"/>@break
        @case('download')<path d="M12 4v11M7 10l5 5 5-5M4 20h16"/>@break
    @endswitch
</svg>
