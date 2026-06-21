@php
    $imageUrl = $product->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image)
        : 'https://placehold.co/600x450?text=' . urlencode($product->name);
@endphp
<div class="card h-100 shadow-sm card-product">
    <a href="{{ route('products.show', $product->slug) }}" class="img-wrap d-block">
        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
        @if ($product->category)
            <span class="chip">{{ $product->category->name }}</span>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title h6 mb-2">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h5>
        <p class="card-text text-secondary small flex-grow-1">
            {{ \Illuminate\Support\Str::limit($product->short_description, 80) }}
        </p>
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm mt-auto align-self-start">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>
