@extends('layouts.app')

@section('title', 'Produk — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Katalog Produk</h1>

    <div class="row">
        {{-- Sidebar filters --}}
        <div class="col-lg-3 mb-4">
            <form method="GET" action="{{ route('products.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="{{ $keyword ?? '' }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="list-group">
                <a href="{{ route('products.index') }}"
                   class="list-group-item list-group-item-action {{ ! $activeCategory ? 'active' : '' }}">
                    Semua Kategori
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="list-group-item list-group-item-action {{ $activeCategory && $activeCategory->id === $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Product grid --}}
        <div class="col-lg-9">
            @if ($activeCategory)
                <p class="text-muted">Kategori: <strong>{{ $activeCategory->name }}</strong></p>
            @endif

            @if ($products->isEmpty())
                <div class="alert alert-info">Belum ada produk yang tersedia.</div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
