@php($title = $product->seo->title ?? $product->trademark ?? "")
@php($description = $product->seo->description ?? null)

@extends('eshop::customer.layouts.master', ['title' =>  $title])

@push('meta')
    <link rel="canonical" href="{{ productRoute($product, $category) }}">
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ productRoute($product, $category, $locale) }}"/>
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
    <meta property="og:url" content="{{ productRoute($product, $category) }}">
    @if($product->image)
        <meta property="og:image" content="{{ $product->image->url() }}">
    @endif
    <meta name="twitter:card" content="summary"/>

    <meta name='robots' content='index, follow'/>
@endpush

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="$product"/>

    <main class="container-fluid bg-white gx-0">
        <section class="container-xxl py-4">
            <div class="row row-cols-1 row-cols-md-2 g-5">
                <div class="col">
                    @include('eshop::customer.product.partials.images')
                </div>

                <div class="col">
                    @can('Manage products')
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
                            @include('eshop::customer.product.partials.product-category')
                            @includeWhen(config('eshop.product.show_manufacturer') && isset($product->manufacturer), 'eshop::customer.product.partials.product-manufacturer')
                        </div>

                        @includeWhen($description = $product->description, 'eshop::customer.product.partials.product-description', ['description' => $description])

                        <div class="d-grid gap-2">
                            @if($product->has_variants)
                                @if($product->variants->min('net_value') !== $product->variants->max('net_value'))
                                    <div class="fs-3 fw-500">{{ format_currency($product->variants->min('net_value')) }} - {{ format_currency($product->variants->max('net_value')) }}</div>
                                @else
                                    <div class="fs-3 fw-500">{{ format_currency($product->variants->min('net_value')) }}</div>
                                @endif
                                @if($product->variants_display === 'grid')
                                    <a href="#product-variants" class="btn btn-primary btn-block">{{ __("See all variants") }} ({{ $product->variants->count() }})</a>
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

                            <div class="small text-gray-600 mt-3">
                                <em class="fa fa-exclamation-circle"></em>
                                {{ __("eshop::product.image_color_diff") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if($product->has_variants && $product->variants_display === 'grid')
            <section class="container-fluid bg-light py-3">
                <div id="product-variants" class="container-xxl">
                    <h2 class="fs-5 border-bottom mb-3 py-3">{{ __("Variants") }}</h2>

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-4">
                        @foreach($variants as $variant)
                            @livewire('product.product-variant', ['product' => $variant, 'category' => $category])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection
