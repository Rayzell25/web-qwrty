<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Terapkan tema tersimpan SEBELUM render (anti-flash) --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('rz-theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', t);
            } catch (e) {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <title>@yield('title', setting('meta_title', setting('site_name', config('app.name', 'RPD'))))</title>

    <meta name="description" content="@yield('meta_description', setting('meta_description', setting('site_tagline', '')))">

    @if ($favicon = setting_asset('favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Theme --}}
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="scroll-progress"></div>

    @include('partials.navbar')

    <main>
        @if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
            <div class="container pt-4">
                @include('partials.flash')
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <button type="button" class="to-top" aria-label="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Theme JS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
