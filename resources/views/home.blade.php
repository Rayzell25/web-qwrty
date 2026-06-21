@extends('layouts.app')

@section('title', setting('site_name', config('app.name', 'RPD')) . ' — ' . setting('site_tagline', 'Selamat Datang'))

@section('content')
    {{-- Hero / Banner --}}
    @if ($banners->isNotEmpty())
        <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    @php
                        $bg = $banner->image
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
                            : null;
                    @endphp
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="hero py-5" @if ($bg) style="background-image: linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)), url('{{ $bg }}'); background-size: cover; background-position: center;" @endif>
                            <div class="container py-5 text-center">
                                <h1 class="display-5 fw-bold">{{ $banner->title }}</h1>
                                @if ($banner->subtitle)
                                    <p class="lead mb-4">{{ $banner->subtitle }}</p>
                                @endif
                                @if ($banner->button_text && $banner->button_link)
                                    <a href="{{ $banner->button_link }}" class="btn btn-light btn-lg">{{ $banner->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @else
        <div class="hero py-5 mb-5">
            <div class="container py-5 text-center">
                <h1 class="display-5 fw-bold">{{ setting('hero_title', 'Selamat Datang di ' . setting('site_name', config('app.name', 'RPD'))) }}</h1>
                <p class="lead mb-4">{{ setting('hero_subtitle', setting('site_tagline', 'Produk terbaik untuk Anda.')) }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Lihat Produk</a>
            </div>
        </div>
    @endif

    <div class="container">
        {{-- Featured products --}}
        @if ($featuredProducts->isNotEmpty())
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">Produk Unggulan</h2>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">Semua Produk</a>
                </div>
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Latest products --}}
        @if ($latestProducts->isNotEmpty())
            <section class="mb-5">
                <h2 class="h4 mb-3">Produk Terbaru</h2>
                <div class="row g-4">
                    @foreach ($latestProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- FAQ preview --}}
        @if ($faqs->isNotEmpty())
            <section class="mb-5">
                <h2 class="h4 mb-3">Pertanyaan Umum</h2>
                <div class="accordion" id="faqPreview">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapse{{ $faq->id }}" aria-expanded="false"
                                        aria-controls="faqCollapse{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse"
                                 aria-labelledby="faqHeading{{ $faq->id }}" data-bs-parent="#faqPreview">
                                <div class="accordion-body">{{ $faq->answer }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua FAQ</a>
                </div>
            </section>
        @endif

        {{-- CTA --}}
        <section class="mb-5">
            <div class="p-5 bg-light rounded-3 text-center">
                <h2 class="h4">Butuh bantuan atau ingin klaim garansi?</h2>
                <p class="text-muted">Tim kami siap membantu Anda.</p>
                <a href="{{ route('contact.index') }}" class="btn btn-primary">Hubungi Kami</a>
                <a href="{{ route('warranty.index') }}" class="btn btn-outline-primary">Klaim Garansi</a>
            </div>
        </section>
    </div>
@endsection
