<nav class="layout-navbar shadow-none py-0">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
            <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="icon-base bx bx-menu icon-lg align-middle text-heading fw-medium"></i>
                </button>
                <a href="{{ route('landing.home') }}" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <span class="text-primary">
                            <img src="{{ $appSettings['sidebar_logo'] }}" alt="Logo" loading="lazy"
                                width="25" />
                        </span>
                    </span>
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ $appSettings['sidebar_name'] }}</span>
                </a>
            </div>

            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="icon-base bx bx-x icon-lg"></i>
                </button>
                <ul class="navbar-nav me-auto">
                    @foreach ($menus as $menu)
                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ $currentRouteName === $menu['route'] ? 'active' : '' }}"
                                aria-current="page" href="{{ route($menu['route']) }}">{{ $menu['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="landing-menu-overlay d-lg-none"></div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                        data-bs-toggle="dropdown" aria-label="Toggle theme (system)">
                        <i class="bx-desktop icon-base bx icon-lg theme-icon-active"></i>
                        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="light"
                                aria-pressed="false">
                                <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
                                aria-pressed="false">
                                <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center active"
                                data-bs-theme-value="system" aria-pressed="true">
                                <span>
                                    <i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>
                                    System
                                </span>
                            </button>
                        </li>
                    </ul>
                </li>

                <li>
                    @if ($isLoggedIn)
                        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
                            <span class="tf-icons icon-base bx bx-user scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <span class="tf-icons icon-base bx bx-log-in-circle scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Login/Register</span>
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>
