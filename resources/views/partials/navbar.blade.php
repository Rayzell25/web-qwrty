@php $siteName = setting('site_name', config('app.name', 'RPD')); @endphp
<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand rz-logo" href="{{ route('home') }}">
            @if ($logo = setting_asset('logo'))
                <img src="{{ $logo }}" alt="{{ $siteName }}" height="38">
                <span class="rz-logo-text">{{ $siteName }}</span>
            @else
                <span class="rz-mark">
                    <span class="rz-r">R</span>
                    <svg class="rz-bolt" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M13 1.5 4.5 13.2c-.3.4 0 .9.5.9H10l-1.6 8.1c-.1.6.7 1 1.1.4L19.5 10.8c.3-.4 0-.9-.5-.9H14l1.6-8c.1-.6-.7-1-1.1-.4z"/>
                    </svg>
                </span>
                <span class="rz-logo-text">{{ $siteName }}</span>
            @endif
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
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
