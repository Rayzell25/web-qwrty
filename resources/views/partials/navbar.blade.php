@php
    $siteName  = setting('site_name', config('app.name', 'RPD'));
    $brandName = setting('site_name');                 // null/empty jika dikosongkan admin
    $logoUrl   = setting_asset('logo');
    $logoExt   = $logoUrl ? strtolower(pathinfo(parse_url($logoUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION)) : '';
    $isVideo   = in_array($logoExt, ['mp4', 'webm', 'ogg', 'mov']);
@endphp
<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand rz-logo" href="{{ route('home') }}">
            @if ($logoUrl)
                @if ($isVideo)
                    <video class="rz-logo-media" autoplay muted loop playsinline disablepictureinpicture>
                        <source src="{{ $logoUrl }}">
                    </video>
                @else
                    <img class="rz-logo-media" src="{{ $logoUrl }}" alt="{{ $brandName ?: 'Logo' }}">
                @endif
                @if (filled($brandName))
                    <span class="rz-logo-text">{{ $brandName }}</span>
                @endif
            @else
                <span class="rz-logo-text">{{ $brandName ?: $siteName }}</span>
            @endif
        </a>

        <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size: 1.7rem; color: var(--rz-ink);"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}" href="{{ route('leaderboard.index') }}">Leaderboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('faq.*') ? 'active' : '' }}" href="{{ route('faq.index') }}">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}" href="{{ route('invoice.index') }}">Cek Invoice</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('warranty.*') ? 'active' : '' }}" href="{{ route('warranty.index') }}">Garansi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Kontak</a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-lg-center mb-2 mb-lg-0">
                <li class="nav-item me-lg-2 my-2 my-lg-0">
                    <button class="theme-toggle" type="button" id="themeToggle" aria-label="Ganti tema gelap atau terang" title="Tema gelap / terang">
                        <i class="bi bi-moon-stars-fill icon-dark"></i>
                        <i class="bi bi-sun-fill icon-light"></i>
                    </button>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                            @if (! auth()->user()->hasVerifiedOtp())
                                <li><a class="dropdown-item" href="{{ route('otp.verify') }}">Verifikasi OTP</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            @if (auth()->user()->is_admin)
                                <li><a class="dropdown-item" href="/admin">Panel Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
