<li class="menu-item {{ $isActive ? 'active' : '' }}">
    <a class="menu-link menu-pointer" data-route="{{ route($route) }}" id="sidebar-menu-page-{{ $id }}{{ $suffix ?? '' }}">
        <div>{{ $name ?? '-' }}</div>
    </a>
</li>
