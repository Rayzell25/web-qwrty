@extends('layouts.app')

@section('title', setting('site_name', config('app.name', 'RPD')) . ' — ' . setting('site_tagline', 'Selamat Datang'))

@section('content')
    {{-- ============ HERO ============ --}}
    @if ($banners->isNotEmpty())
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    @php
                        $bg = $banner->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image) : null;
                    @endphp
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="hero py-5" @if ($bg) style="background-image: linear-gradient(rgba(12,16,35,.72), rgba(49,46,129,.78)), url('{{ $bg }}'); background-size: cover; background-position: center;" @endif>
                            <div class="container py-5">
                                <div class="row align-items-center" style="min-height: 320px;">
                                    <div class="col-lg-8">
                                        <span class="hero-badge"><i class="bi bi-patch-check-fill"></i> Garansi Resmi & Terpercaya</span>
                                        <h1 class="mb-3">{{ $banner->title }}</h1>
                                        @if ($banner->subtitle)
                                            <p class="lead mb-4" style="max-width: 620px;">{{ $banner->subtitle }}</p>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($banner->button_text && $banner->button_link)
                                                <a href="{{ $banner->button_link }}" class="btn btn-light btn-lg">{{ $banner->button_text }}</a>
                                            @else
                                                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Lihat Produk</a>
                                            @endif
                                            <a href="{{ route('warranty.index') }}" class="btn btn-lg btn-outline-light text-white">Klaim Garansi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span><span class="visually-hidden">Prev</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span><span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @else
        <div class="hero py-5">
            <div class="container py-5 text-center" style="max-width: 820px;">
                <span class="hero-badge"><i class="bi bi-patch-check-fill"></i> Garansi Resmi & Terpercaya</span>
                <h1 class="mb-3">{{ setting('hero_title', 'Selamat Datang di ' . setting('site_name', config('app.name', 'RPD'))) }}</h1>
                <p class="lead mb-4 mx-auto">{{ setting('hero_subtitle', setting('site_tagline', 'Produk terbaik dengan kualitas terjamin.')) }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Lihat Produk</a>
                    <a href="{{ route('contact.index') }}" class="btn btn-lg btn-outline-light text-white">Hubungi Kami</a>
                </div>
            </div>
        </div>
    @endif

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
                        ['bi-truck', 'Pengiriman Cepat', 'Proses cepat dan aman sampai ke tangan Anda.'],
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

    {{-- ============ PRODUK UNGGULAN ============ --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="section-tight">
            <div class="container">
                <div class="d-flex justify-content-between align-items-end mb-4 reveal">
                    <div>
                        <span class="eyebrow"><i class="bi bi-stars"></i> Pilihan Terbaik</span>
                        <h2 class="h3 mb-0">Produk Unggulan</h2>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Semua Produk</a>
                </div>
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-6 col-lg-3 reveal d{{ ($loop->index % 4) + 1 }}">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ PRODUK TERBARU ============ --}}
    @if ($latestProducts->isNotEmpty())
        <section class="section-tight">
            <div class="container">
                <div class="mb-4 reveal">
                    <span class="eyebrow"><i class="bi bi-box-seam"></i> Baru Datang</span>
                    <h2 class="h3 mb-0">Produk Terbaru</h2>
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
                                    <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse"
                                         data-bs-parent="#faqPreview">
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
