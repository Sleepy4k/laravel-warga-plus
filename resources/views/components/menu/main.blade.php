<li class="menu-item {{ $isActive ? 'active open' : '' }}">
    <a class="menu-link menu-toggle menu-pointer">
        @if (!empty($icon) || $icon == null)
            <i class="menu-icon tf-icons bx bx-{{ $icon }}"></i>
        @endif
        <div>{{ $name ?? '-' }}</div>
    </a>
    <ul class="menu-sub">
        {{ $slot }}
    </ul>
</li>
