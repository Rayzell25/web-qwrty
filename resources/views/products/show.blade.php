@extends('layouts.app')

@section('title', $product->name . ' — ' . setting('site_name', config('app.name', 'RPD')))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? $product->description ?? ''), 150))

@section('content')
@php
    $mainImage = $product->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image)
        : 'https://placehold.co/800x600?text=' . urlencode($product->name);
    $gallery = collect($product->gallery ?? [])->map(function ($path) {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    });
@endphp
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-6">
            <img src="{{ $mainImage }}" class="img-fluid rounded shadow-sm w-100 object-cover" alt="{{ $product->name }}">

            @if ($gallery->isNotEmpty())
                <div class="row g-2 mt-2">
                    @foreach ($gallery as $img)
                        <div class="col-3">
                            <img src="{{ $img }}" class="img-fluid rounded border" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-md-6">
            @if ($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                   class="badge bg-secondary text-decoration-none mb-2">{{ $product->category->name }}</a>
            @endif
            <h1 class="h3">{{ $product->name }}</h1>

            @if ($product->short_description)
                <p class="lead text-muted">{{ $product->short_description }}</p>
            @endif

            <a href="{{ route('warranty.index') }}" class="btn btn-primary">Klaim Garansi</a>
            <a href="{{ route('contact.index') }}" class="btn btn-outline-primary">Tanya Produk</a>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc"
                            type="button" role="tab">Deskripsi</button>
                </li>
                @if ($product->specification)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec"
                                type="button" role="tab">Spesifikasi</button>
                    </li>
                @endif
            </ul>
            <div class="tab-content border border-top-0 p-4">
                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                    {!! nl2br(e($product->description ?? 'Belum ada deskripsi.')) !!}
                </div>
                @if ($product->specification)
                    <div class="tab-pane fade" id="spec" role="tabpanel">
                        {!! nl2br(e($product->specification)) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-5">
            <h2 class="h4 mb-3">Produk Terkait</h2>
            <div class="row g-4">
                @foreach ($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        @include('partials.product-card', ['product' => $related])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
