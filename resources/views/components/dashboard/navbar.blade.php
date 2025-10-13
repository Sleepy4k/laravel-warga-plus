<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link px-0" href="javascript:void(0);">
                    <span class="d-none d-md-inline-block">
                        Welcome, {{ $personal->full_name }}!
                    </span>
                </a>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item me-2 me-xl-0 ">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class="bx bx-sm centerized-nav"></i>
                </a>
            </li>

            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                <button class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                    style="border: none; background: none;">
                    <i class="icon-base bx bx-globe icon-md centerized-nav"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item active" data-language="en" id="change-language-en">
                            <span>English</span>
                        </button>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bx bx-grid-alt bx-sm centerized-nav"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                            <a data-route="{{ route('profile.shortcut.index') }}" id="shortcut-add-button"
                                class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Add shortcuts" style="cursor: pointer;"><i
                                    class="bx bx-sm bx-plus-circle"></i></a>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">
                        @forelse ($shortcuts->chunk(2) as $shortcutRow)
                            <div class="row row-bordered overflow-visible g-0">
                                @foreach ($shortcutRow as $shortcut)
                                    @if (!empty($shortcut->permissions))
                                        @canany($shortcut->permissions)
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="bx bx-{{ $shortcut['icon'] }} fs-4"></i>
                                                </span>
                                                <a data-route="{{ route($shortcut['route']) }}" class="stretched-link"
                                                    id="shortcut-link-{{ rand(1000, 9999) }}"
                                                    style="cursor: pointer;">{{ $shortcut['label'] }}</a>
                                                <small class="text-muted mb-0">{{ $shortcut['description'] }}</small>
                                            </div>
                                        @endcanany
                                    @else
                                        <div class="dropdown-shortcuts-item col">
                                            <span
                                                class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                <i class="bx bx-{{ $shortcut['icon'] }} fs-4"></i>
                                            </span>
                                            <a data-route="{{ route($shortcut['route']) }}" class="stretched-link"
                                                id="shortcut-link-{{ rand(1000, 9999) }}"
                                                style="cursor: pointer;">{{ $shortcut['label'] }}</a>
                                            <small class="text-muted mb-0">{{ $shortcut['description'] }}</small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @empty
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                        <i class="bx bx-error-circle fs-4 text-warning"></i>
                                    </span>
                                    <span class="text-muted mb-0">No shortcuts available</span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </li>

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online position-relative" id="user-avatar"
                        style="width:40px; height:40px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                        <img src="{{ $personal->userAvatar() }}" alt="User Avatar"
                            class="w-100 h-100 object-fit-cover rounded-circle border border-2 border-primary shadow-sm"
                            loading="lazy" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online position-relative" id="user-avatar-drop"
                                        style="width:40px; height:40px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                        <img src="{{ $personal->userAvatar() }}" alt="User Avatar"
                                            class="w-100 h-100 object-fit-cover rounded-circle border border-2 border-primary shadow-sm"
                                            loading="lazy" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ ucfirst($user->username) }}</span>
                                    <small class="text-muted">{{ ucfirst($role) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    @foreach ($dropdownItems as $menu)
                        @if ($loop->first && !$menu->is_spacer)
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                        @endif

                        @if ($menu->is_spacer)
                            @if (empty($menu->meta?->permissions))
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                            @else
                                @canany($menu->meta?->permissions ?? [])
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                @endcanany
                            @endif
                            @continue
                        @endif

                        @php
                            $isMenuActive = $currentRouteName == $menu->meta?->active_routes;
                            $menuPermissions = $menu->meta?->permissions ?? [];
                            $showMenu =
                                empty($menuPermissions) ||
                                (is_array($menuPermissions) && count($menuPermissions) === 0);
                        @endphp

                        @if ($showMenu)
                            <li>
                                <a data-route="{{ route($menu->meta?->route) }}"
                                    class="dropdown-item d-flex align-items-center {{ $menu->meta?->route == $currentRouteName ? 'active' : '' }}"
                                    id="navbar-link-{{ rand(1000, 9999) }}" style="cursor: pointer;">
                                    <i class="icon-base icon-md me-3 bx bx-{{ $menu->meta?->icon }}"></i>
                                    <span class="align-middle w-100 text-start">{{ $menu->name }}</span>
                                </a>
                            </li>
                        @else
                            @canany($menuPermissions)
                                <li>
                                    <a data-route="{{ route($menu->meta?->route) }}"
                                        class="dropdown-item d-flex align-items-center {{ $menu->meta?->route == $currentRouteName ? 'active' : '' }}"
                                        id="navbar-link-{{ rand(1000, 9999) }}" style="cursor: pointer;">
                                        <i class="icon-base icon-md me-3 bx bx-{{ $menu->meta?->icon }}"></i>
                                        <span class="align-middle w-100 text-start">{{ $menu->name }}</span>
                                    </a>
                                </li>
                            @endcanany
                        @endif

                        @if ($loop->last && !$menu->is_spacer)
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                        @endif
                    @endforeach
                    <li>
                        <button class="dropdown-item text-danger d-flex align-items-center" id="logout-btn">
                            <i class="icon-base icon-md me-3 bx bx-power-off"></i>
                            <span class="align-middle w-100 text-start">Logout</span>
                        </button>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
