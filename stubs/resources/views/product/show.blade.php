@php($title = $product->seo->title ?? $product->trademark ?? "")
@php($description = $product->seo->description ?? null)

@extends('layouts.master', ['title' =>  $title])

@push('meta')
    <link rel="canonical" href="{{ productRoute($product, $category) }}">
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ productRoute($product, $category, locale: $locale) }}"/>
    @endforeach

    <script type="application/ld+json">{!! schema()->breadcrumb($category, $product) !!}</script>
    <script type="application/ld+json">{!! schema()->product($product) !!}</script>
    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>

    @if(!empty($description))
        <meta name="description" content="{{ $description }}">
    @endif

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @if(!empty($description))
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:type" content="website">
    @if($product->image)
        <meta property="og:image" content="{{ $product->image->url() }}">
    @endif
    <meta name="twitter:card" content="summary"/>

    <meta name='robots' content='index, follow'/>
@endpush

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <script src="{{ mix('js/fslightbox.js') }}"></script>
@endpush

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="$product" :variant="null"/>

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

                    <div class="d-grid gap-2 align-self-start">
                        <h1 class="fs-3 mb-0"><strong class="fw-500">{{ $product->trademark }}</strong></h1>

                        <div class="small text-secondary fw-500">{{ __("Code") }}: {{ $product->sku }}</div>

                        <div class="vstack gap-1 my-2">
                            @include('product.partials.product-category')
                            @includeWhen(isset($product->manufacturer), 'product.partials.product-manufacturer')
                        </div>

                        @includeWhen($description = $product->description, 'product.partials.product-description', ['description' => $description])

                        <div class="d-grid gap-2">
                            @if($product->has_variants)
                                @if($product->variants_display === 'grid')
                                    <a href="#product-variants" class="btn btn-primary btn-block">{{ __("See all variants") }} ({{ $product->variants_count }})</a>
                                @endif

                                @if($product->variants_display === 'buttons')
                                    <livewire:product.product-variants-buttons :product="$product"/>
                                @endif

                                @if($product->variants_display === 'list')
                                @endif
                            @elseif($product->canBeBought())
                                <livewire:product.add-to-cart-form :product="$product"/>
                            @else
                                <div class="col-12 mb-3 hstack gap-3">
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
            <livewire:product.product-variants :product="$product" :category="$category"/>
        </div>
    @endif
@endsection