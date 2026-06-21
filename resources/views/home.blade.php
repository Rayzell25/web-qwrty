@extends('layouts.app')

@php
    $siteName = setting('site_name', config('app.name', 'RPD'));
    $carousel = $featuredProducts->isNotEmpty() ? $featuredProducts : $latestProducts;
@endphp

@section('title', $siteName . ' — ' . setting('site_tagline', 'Selamat Datang'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush

@section('content')
    {{-- ============ HERO TERANG ============ --}}
    <section class="hero-light">
        <div class="hero-grid"></div>
        <div class="container text-center">
            <span class="online-badge reveal"><span class="dot"></span> {{ $siteName }} Online 24 Jam</span>

            <h1 class="hero-title mt-4 reveal d1">
                {{ setting('hero_title', 'Belanja Aman,') }}<br>
                <span class="text-gradient">{{ setting('hero_title_accent', 'Garansi Terpercaya') }}</span>
            </h1>

            <p class="hero-sub reveal d2">
                {{ setting('hero_subtitle', setting('site_tagline', 'Produk berkualitas dengan garansi resmi, layanan cepat & dukungan ramah setiap hari.')) }}
            </p>

            @if ($carousel->isNotEmpty())
                <div class="reveal d3 mt-4">
                    <span class="popular-pill"><i class="bi bi-fire"></i> PALING POPULER</span>
                </div>

                <div class="reveal d3">
                    <div class="swiper feat-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($carousel as $product)
                                @php
                                    $img = $product->image
                                        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image)
                                        : 'https://placehold.co/600x800?text=' . urlencode($product->name);
                                @endphp
                                <div class="swiper-slide">
                                    <a href="{{ route('products.show', $product->slug) }}" class="feat-card">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                        <span class="cap">{{ $product->name }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2 justify-content-center mt-4 reveal d4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Semua Produk</a>
                <a href="{{ route('warranty.index') }}" class="btn btn-outline-primary btn-lg">Klaim Garansi</a>
            </div>
        </div>
    </section>

    {{-- ============ STAT BAR ============ --}}
    <div class="container">
        <div class="row g-4 py-4 py-lg-5 text-center reveal">
            <div class="col-6 col-lg-3 stat">
                <div class="num">{{ \App\Models\Product::active()->count() }}+</div>
                <div class="lbl">Produk Tersedia</div>
            </div>
            <div class="col-6 col-lg-3 stat">
                <div class="num">{{ \App\Models\Category::active()->count() }}</div>
                <div class="lbl">Kategori</div>
            </div>
            <div class="col-6 col-lg-3 stat">
                <div class="num">100%</div>
                <div class="lbl">Garansi Resmi</div>
            </div>
            <div class="col-6 col-lg-3 stat">
                <div class="num">24/7</div>
                <div class="lbl">Dukungan</div>
            </div>
        </div>
    </div>

    {{-- ============ KEUNGGULAN ============ --}}
    <section class="section-tight">
        <div class="container">
            <div class="row g-4">
                @php
                    $features = [
                        ['bi-shield-check', 'Garansi Resmi', 'Setiap produk bergaransi resmi dan dapat diklaim secara online dengan mudah.'],
                        ['bi-lightning-charge-fill', 'Proses Cepat', 'Layanan kilat, pesanan & klaim diproses secepat mungkin.'],
                        ['bi-headset', 'Dukungan Ramah', 'Tim kami siap membantu kapan pun Anda butuh.'],
                    ];
                @endphp
                @foreach ($features as $i => $f)
                    <div class="col-md-4 reveal d{{ $i + 1 }}">
                        <div class="tile">
                            <div class="ico"><i class="bi {{ $f[0] }}"></i></div>
                            <h3 class="h5">{{ $f[1] }}</h3>
                            <p class="text-secondary mb-0">{{ $f[2] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PRODUK TERBARU ============ --}}
    @if ($latestProducts->isNotEmpty())
        <section class="section-tight">
            <div class="container">
                <div class="d-flex justify-content-between align-items-end mb-4 reveal">
                    <div>
                        <span class="eyebrow"><i class="bi bi-box-seam"></i> Koleksi</span>
                        <h2 class="h3 mb-0">Produk Terbaru</h2>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Semua Produk</a>
                </div>
                <div class="row g-4">
                    @foreach ($latestProducts as $product)
                        <div class="col-6 col-lg-3 reveal d{{ ($loop->index % 4) + 1 }}">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FAQ ============ --}}
    @if ($faqs->isNotEmpty())
        <section class="section-tight">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-4 reveal">
                            <span class="eyebrow"><i class="bi bi-question-circle"></i> Bantuan</span>
                            <h2 class="h3 mb-0">Pertanyaan Umum</h2>
                        </div>
                        <div class="accordion reveal" id="faqPreview">
                            @foreach ($faqs as $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#faqCollapse{{ $faq->id }}" aria-expanded="false">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqPreview">
                                        <div class="accordion-body text-secondary">{{ $faq->answer }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-4 reveal">
                            <a href="{{ route('faq.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua FAQ</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="section-tight">
        <div class="container">
            <div class="cta-panel text-center reveal reveal-zoom">
                <h2 class="h2 mb-2">Butuh bantuan atau ingin klaim garansi?</h2>
                <p class="mb-4" style="color: rgba(255,255,255,.85);">Tim kami siap membantu Anda kapan saja.</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('contact.index') }}" class="btn btn-light btn-lg">Hubungi Kami</a>
                    <a href="{{ route('warranty.index') }}" class="btn btn-lg btn-outline-light text-white">Klaim Garansi</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined' && document.querySelector('.feat-swiper')) {
                new Swiper('.feat-swiper', {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    loop: true,
                    speed: 600,
                    coverflowEffect: { rotate: 0, stretch: 36, depth: 130, modifier: 1.5, slideShadows: false },
                    autoplay: { delay: 2800, disableOnInteraction: false },
                    navigation: { nextEl: '.feat-swiper .swiper-button-next', prevEl: '.feat-swiper .swiper-button-prev' },
                });
            }
        });
    </script>
@endpush
