@php
    $title = $product->seo ? $product->seo->title : ($product->option_values ?? "");
    if($product->parent &&  $product->parent->seo) {
        $title = trim($product->parent->seo->title . ' ' . $title);
    }

    $description = $product->seo->description ?? $product->parent->seo->description ?? null;
@endphp

@extends('eshop::customer.layouts.master', ['title' =>  $title])

@push('meta')
    <link rel="canonical" href="{{ productRoute($product->parent, $category) }}">
    @foreach(array_keys(eshop('locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ productRoute($product->parent, $category, $locale) }}"/>
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
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ productRoute($product, $category) }}">
    @if($product->image)
        <meta property="og:image" content="{{ $product->image->url($product->has_watermark ? 'wm' : null) }}">
    @endif

    <meta name="twitter:card" content="product"/>
    <meta name="twitter:title" content="{{ $title }}">
    @if($product->image)
        <meta name="twitter:image" content="{{ $product->image->url($product->has_watermark ? 'wm' : null) }}">
    @endif
    @if(!empty($description))
        <meta name="twitter:description" content="{{ $description }}">
    @endif

    <meta name="robots" content="index, follow"/>
@endpush

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="$product"/>

    <main class="container-fluid bg-white">
        <section class="container-xxl py-4">
            <div class="row row-cols-1 row-cols-md-2 g-5">
                <div class="col">
                    @include('eshop::customer.product.partials.images')
                </div>

                <div class="col">
                    @can('Manage products')
                        <div class="d-flex gap-3 mb-2">
                            <a href="{{ route('variants.edit', $product) }}" class="text-decoration-none">
                                <em class="far fa-edit me-1"></em>{{ __('eshop::buttons.edit') }}
                            </a>
                        </div>
                    @endcan

                    <div class="d-grid gap-2 align-self-start">
                        <h1 class="fs-3 mb-0 fw-500">{{ $product->trademark }}</h1>

                        <div class="small text-secondary fw-500">{{ __("Code") }}: {{ $product->sku }}</div>

                        <div class="vstack gap-1 my-2">
                            @include('eshop::customer.product.partials.product-category')
                            @include('eshop::customer.product.partials.product-parent')
                            @includeWhen(eshop('product.show_manufacturer') && isset($product->manufacturer), 'eshop::customer.product.partials.product-manufacturer')
                        </div>

                        @includeWhen($description = $product->description ?? $product->parent->description, 'eshop::customer.product.partials.product-description', ['description' => $description])

                        <div class="d-grid gap-2">
                            @if($product->canBeBought())
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
        </section>
    </main>
@endsection
