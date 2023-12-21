@php($title = $product->seo->title ?? $product->trademark ?? "")
@php($description = $product->seo->description ?? null)

@extends('eshop::customer.layouts.master', ['title' =>  $title])

@include('eshop::customer.product.partials.product-meta')

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="$product"/>

    <main class="container-fluid bg-white">
        <div class="container-xxl py-4">
            <div class="row row-cols-1 row-cols-md-2 g-5">
                <div class="col">
                    @include('eshop::customer.product.partials.images')
                </div>

                <div class="col">
                    <div class="d-grid gap-2 align-self-start">
                        <h1 class="fs-3 mb-0">{{ $product->trademark }}</h1>
                        @if(config('eshop.product.show_sku'))
                            <div class="product-sku">{{ __("Code") }}: {{ $product->sku }}</div>
                        @endif

                        @can('Manage products')
                            <div class="d-flex gap-3 mb-2 small text-secondary">
                                <a href="{{ $product->isVariant() ? route('variants.edit', $product) : route('products.edit', $product) }}" class="text-decoration-none">
                                    <em class="far fa-edit me-1"></em>{{ __('eshop::buttons.edit') }}
                                </a>
                            </div>
                        @endcan

                        <div class="vstack gap-1 my-2">
                            @include('eshop::customer.product.partials.product-category')
                            @includeWhen(eshop('product.show_manufacturer') && isset($product->manufacturer), 'eshop::customer.product.partials.product-manufacturer')
                        </div>

                        @includeWhen($description = $product->description, 'eshop::customer.product.partials.product-description', ['description' => $description])

                        <div class="d-grid gap-2">
                            @if($product->has_variants)
                                @if($product->variants_display === 'grid')
                                    <div class="fs-3 fw-500">
                                        {{ format_currency($min = $product->variants->min('net_value')) }}

                                        @if($min !== ($max = $product->variants->max('net_value')))
                                            - {{ format_currency($max) }}
                                        @endif
                                    </div>
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
                                    @if($product->discount > 0)
                                        <s class="text-secondary">{{ format_currency($product->price) }}</s>
                                    @endif
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
        </div>
    </main>

    @if($product->has_variants && $product->variants_display === 'grid')
        <section class="container-fluid bg-light py-3">
            <div id="product-variants" class="container-xxl">
                <h2 class="fs-5 border-bottom mb-3 py-3">{{ __("Variants") }}</h2>

                <ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-4 list-unstyled">
                    @foreach($variants as $variant)
                        @livewire('product.product-variant', ['product' => $variant, 'category' => $category])
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if(eshop('product.suggestions'))
        <section class="container-fluid bg-white py-4">
            <div class="container-xxl">
                <x-more-category-products :product="$product"/>
            </div>
        </section>
    @endif
@endsection
