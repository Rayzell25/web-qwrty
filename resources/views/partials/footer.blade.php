@php
    $siteName = setting('site_name', config('app.name', 'RPD'));
    $socialHeading = setting('social_heading', '');
    $socialLinks = \App\Models\SocialLink::activeForDisplay();
@endphp

<footer class="site-footer pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            {{-- Brand & info --}}
            <div class="col-lg-4">
                <div class="foot-brand h4 mb-2">{{ $siteName }}</div>
                <p class="text-secondary mb-3" style="max-width: 320px;">
                    {{ setting('site_tagline', 'Produk berkualitas dengan garansi resmi.') }}
                </p>
                @if ($address = setting('company_address'))
                    <p class="mb-1 small"><i class="bi bi-geo-alt me-2"></i>{{ $address }}</p>
                @endif
                @if ($phone = setting('company_phone'))
                    <p class="mb-1 small"><i class="bi bi-telephone me-2"></i>{{ $phone }}</p>
                @endif
                @if ($email = setting('company_email'))
                    <p class="mb-1 small"><i class="bi bi-envelope me-2"></i>{{ $email }}</p>
                @endif
            </div>

            {{-- Navigasi --}}
            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Navigasi</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                    <li><a href="{{ route('leaderboard.index') }}">Leaderboard</a></li>
                    <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                </ul>
            </div>

            {{-- Layanan --}}
            <div class="col-6 col-lg-2">
                <h6 class="foot-brand mb-3">Layanan</h6>
                <ul class="list-unstyled d-grid gap-2 small">
                    <li><a href="{{ route('invoice.index') }}">Cek Invoice</a></li>
                    <li><a href="{{ route('warranty.index') }}">Klaim Garansi</a></li>
                    <li><a href="{{ route('contact.index') }}">Kontak</a></li>
                </ul>
            </div>

            {{-- Tautan sosial — hanya muncul kalau ADA tautan aktif yang ditambahkan di admin --}}
            @if ($socialLinks->isNotEmpty())
                <div class="col-lg-4">
                    @if (filled($socialHeading))
                        <h6 class="foot-brand mb-3">{{ $socialHeading }}</h6>
                    @endif
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($socialLinks as $s)
                            <a class="soc"
                               href="{{ $s->href }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="{{ $s->platform_label }}"
                               title="{{ $s->label ?: $s->platform_label }}">
                                <i class="bi {{ $s->icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <hr class="my-4" style="border-color: rgba(255,255,255,.08);">

        <p class="text-center text-secondary small mb-0">
            {{ setting('footer_text', '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.') }}
        </p>
    </div>
</footer>
