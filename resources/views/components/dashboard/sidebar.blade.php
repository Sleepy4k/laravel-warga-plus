<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <button class="app-brand-link" data-route="{{ route('landing.home') }}" id="sidebar-menu-page-main"
            style="border: none; background: none; padding: 0;">
            <span class="app-brand-logo demo">
                <img src="{{ $appSettings['app_logo'] }}" alt="Logo" loading="lazy" width="35" />
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2"
                style="font-size: {{ $appSettings['sidebar_name_size'] }}rem;">{{ $appSettings['sidebar_name'] }}</span>
        </button>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menus as $index => $menu)
            @if ($menu['is_spacer'])
                @canany($menu['meta']['permissions'] ?? [])
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ $menu['name'] }}</span>
                    </li>
                @endcanany
                @continue
            @endif

            @php
                $childMenuActive = collect($menu['children'])->contains(
                    fn($child) => $currentRouteName == $child['meta']['active_routes'],
                );
                $isMenuActive = $currentRouteName == $menu['meta']['active_routes'] || $childMenuActive;
                $menuPermissions = $menu['meta']['permissions'] ?? [];
                $showMenu = empty($menuPermissions) || (is_array($menuPermissions) && count($menuPermissions) === 0);
            @endphp

            @if ($showMenu)
                <x-menu.main :index="$index" :isActive="$isMenuActive" :icon="$menu['meta']['icon'] ?? null" :name="$menu['name']">
                    @foreach ($menu['children'] as $childIndex => $child)
                        @php
                            $childMenuPermissions = $child['meta']['permissions'] ?? [];
                            $showMenuChild =
                                empty($childMenuPermissions) ||
                                (is_array($childMenuPermissions) && count($childMenuPermissions) === 0);
                        @endphp

                        @if ($showMenuChild)
                            <x-menu.sub :isActive="$currentRouteName == $child['meta']['active_routes']" :route="$child['meta']['route']" :name="$child['name']" :id="$childIndex" />
                        @else
                            @canany($childMenuPermissions)
                                <x-menu.sub :isActive="$currentRouteName == $child['meta']['active_routes']" :route="$child['meta']['route']" :name="$child['name']" :id="$childIndex" />
                            @endcanany
                        @endif
                    @endforeach
                </x-menu.main>
            @else
                @canany($menuPermissions)
                    <x-menu.main :index="$index" :isActive="$isMenuActive" :icon="$menu['meta']['icon'] ?? null" :name="$menu['name']">
                        @foreach ($menu['children'] as $childIndex => $child)
                            @php
                                $childMenuPermissions = $child['meta']['permissions'] ?? [];
                                $showMenuChild =
                                    empty($childMenuPermissions) ||
                                    (is_array($childMenuPermissions) && count($childMenuPermissions) === 0);
                            @endphp

                            @if ($showMenuChild)
                                <x-menu.sub :isActive="$currentRouteName == $child['meta']['active_routes']" :route="$child['meta']['route']" :name="$child['name']" :id="$childIndex" />
                            @else
                                @canany($childMenuPermissions)
                                    <x-menu.sub :isActive="$currentRouteName == $child['meta']['active_routes']" :route="$child['meta']['route']" :name="$child['name']" :id="$childIndex" />
                                @endcanany
                            @endif
                        @endforeach
                    </x-menu.main>
                @endcanany
            @endif
        @endforeach
    </ul>
</aside>
