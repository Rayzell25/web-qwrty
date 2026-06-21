@php
    $imageUrl = $product->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image)
        : 'https://placehold.co/600x400?text=' . urlencode($product->name);
@endphp
<div class="card h-100 shadow-sm card-product">
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}">
    </a>
    <div class="card-body d-flex flex-column">
        @if ($product->category)
            <span class="badge bg-light text-dark align-self-start mb-2">{{ $product->category->name }}</span>
        @endif
        <h5 class="card-title">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h5>
        <p class="card-text text-muted small flex-grow-1">
            {{ \Illuminate\Support\Str::limit($product->short_description, 90) }}
        </p>
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm mt-auto">
            Lihat Detail
        </a>
    </div>
</div>
