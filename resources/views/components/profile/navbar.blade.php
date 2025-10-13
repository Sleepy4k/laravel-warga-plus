<ul class="nav nav-pills flex-column flex-md-row mb-4">
    @foreach ($navItems as $navItem)
        <li class="nav-item">
            <a class="nav-link {{ $currentRouteName === $navItem['route'] ? 'active' : '' }}"
                data-route="{{ route($navItem['route']) }}" style="cursor: pointer;"
                id="profile-nav-{{ $navItem['route'] }}">
                <i class="{{ $navItem['icon'] }} me-1"></i> {{ $navItem['label'] }}
            </a>
        </li>
    @endforeach
</ul>
