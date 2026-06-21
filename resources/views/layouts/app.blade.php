<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', setting('meta_title', setting('site_name', config('app.name', 'RPD'))))</title>

    <meta name="description" content="@yield('meta_description', setting('meta_description', setting('site_tagline', '')))">

    @if ($favicon = setting_asset('favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @endif

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root { --rpd-primary: #0d6efd; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1 0 auto; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); color: #fff; }
        .card-product .card-img-top { height: 200px; object-fit: cover; background: #f1f3f5; }
        .object-cover { object-fit: cover; }
        footer a { text-decoration: none; }
    </style>

    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main class="py-4">
        <div class="container">
            @include('partials.flash')
        </div>

        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
