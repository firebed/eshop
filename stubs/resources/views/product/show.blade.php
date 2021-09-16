@extends('layouts.master', ['title' =>  $product->seo->title ?? $product->trademark])

@push('meta')
    @if(!empty($product->seo->description))
        <meta name="description" content="{{ $product->seo->description }}">
    @endif

    <script type="application/ld+json">{!! $webPage !!}</script>
    @if(!empty($breadcrumb))
        <script type="application/ld+json">{!! $breadcrumb !!}</script>
    @endif
    <script type="application/ld+json">{!! $psd !!}</script>

    <meta property="og:title" content="{{ $product->seo->title ?? $product->trademark }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @if(!empty($product->seo->description))
        <meta property="og:description" content="{{ $product->seo->description }}">
    @endif
    <meta property="og:type" content="website">
    @if($product->image)
        <meta property="og:image" content="{{ $product->image->url() }}">
    @endif
@endpush

@push('header_scripts')
    <link rel="canonical" href="{{ productRoute($product) }}">

    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <script src="{{ mix('js/fslightbox.js') }}"></script>
@endpush

@section('main')
    @if($product->isVariant())
        <x-eshop-category-breadcrumb :category="$category" :product="$product->parent" :variant="$product"/>
    @else
        <x-eshop-category-breadcrumb :category="$category" :product="$product" :variant="null"/>
    @endif

    <div class="container-fluid bg-white py-4">
        <div class="container-xxl">
            <div class="row row-cols-1 row-cols-md-2 g-5">
                <div class="col">
                    @include('product.partials.images')
                </div>

                <div class="col">
                    @can('Edit product')
                        <div class="d-flex gap-3 mb-2">
                            <a href="{{ $product->isVariant() ? route('variants.edit', $product) : route('products.edit', $product) }}" class="text-decoration-none">
                                <em class="far fa-edit me-1"></em>{{ __('eshop::buttons.edit') }}
                            </a>
                        </div>
                    @endcan

                    <div class="d-grid gap-4 align-self-start">
                        <h1 class="fs-3 fw-500 mb-0">{{ $product->trademark }}</h1>

                        @include('product.partials.product-category')
                        @includeWhen($product->isVariant(), 'product.partials.product-parent')
                        @includeWhen(isset($product->manufacturer), 'product.partials.product-manufacturer')

                        @includeWhen($product->description !== NULL, 'product.partials.product-description')

                        <div class="d-grid gap-2">
                            @if($product->has_variants)
                                @if($product->variants_display === 'grid')
                                    <a href="#product-variants" class="btn btn-primary btn-block">{{ __("See all variants") }} ({{ $product->variants_count }})</a>
                                @endif

                                @if($product->variants_display === 'buttons')
                                    <livewire:customer.product.product-variants-buttons :product="$product"/>
                                @endif

                                @if($product->variants_display === 'list')
                                @endif
                            @elseif($product->canBeBought())
                                <livewire:customer.product.add-to-cart-form :product="$product"/>
                            @else
                                <div class="col-12 mb-4 hstack gap-3">
                                    <div class="h3 mb-0">{{ format_currency($product->netValue) }}</div>
                                    @if($product->discount > 0) <s class="text-secondary">{{ format_currency($product->price) }}</s>@endif
                                </div>

                                <button class="btn btn-danger" disabled>{{ __("Out of stock") }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($product->has_variants && $product->variants_display === 'grid')
        <div id="product-variants" class="container-fluid mb-4 py-4 bg-light">
            <livewire:customer.product.product-variants :product="$product" :category="$category"/>
        </div>
    @endif
@endsection