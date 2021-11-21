@php($selectedManufacturersTitle = $selectedManufacturers->pluck('name')->join(', '))

@extends('eshop::customer.layouts.master', ['title' => __($collection->name) . (filled($selectedManufacturersTitle) ? ' ' . $selectedManufacturersTitle : '') . ' - Όλες οι κατηγορίες'])

@push('meta')
    @foreach(array_keys(eshop('locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('products.collections.index', [$locale, $collection->slug]) }}" />
    @endforeach

    <meta name="description" content="Δείτε όλα τα προϊόντων της συλλογής {{ __($collection->name) }} για όλες της κατηγορίες στην καλύτερη τιμή!">

    <script type="application/ld+json">{!! schema()->webPage(__($collection->name) . ' - Όλες οι κατηγορίες', "Δείτε όλα τα προϊόντων της συλλογής {{ __($collection->name) }} για όλες της κατηγορίες στην καλύτερη τιμή!") !!}</script>

    @if($products->onFirstPage())
        <link rel="canonical" href="{{ $canonical = route('products.collections.index', [app()->getLocale(), $collection->slug]) }}">
    @else
        <link rel="canonical" href="{{ $canonical = $products->url($products->currentPage()) }}">
    @endif

    @if($products->currentPage() == 2)
        <link rel="prev" href="{{ route('products.collections.index', app()->getLocale()) }}">
    @elseif($products->currentPage() > 2)
        <link rel="prev" href="{{ $products->previousPageUrl() }}">
    @endif

    @if($products->hasMorePages())
        <link rel="next" href="{{ $products->nextPageUrl() }}">
    @endif

    <meta property="og:title" content="{{ $collection->name }} - Όλες οι κατηγορίες">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="Δείτε όλα τα προϊόντων της συλλογής {{ __($collection->name) }} για όλες της κατηγορίες στην καλύτερη τιμή!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    @include('eshop::customer.layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary" />

    <meta name='robots' content='index, follow' />
@endpush

@push('meta')
    @isset($products)
        @if($products->onFirstPage())
            <link rel="canonical" href="{{ route('products.collections.index', [app()->getLocale(), $collection->slug]) }}">
        @else
            <link rel="canonical" href="{{ $products->url($products->currentPage()) }}">
        @endif

        @if($products->currentPage() == 2)
            <link rel="prev" href="{{ route('products.collections.index', [app()->getLocale(), $collection]) }}">
        @elseif($products->currentPage() > 2)
            <link rel="prev" href="{{ $products->previousPageUrl() }}">
        @endif

        @if($products->hasMorePages())
            <link rel="next" href="{{ $products->nextPageUrl() }}">
        @endif
    @endif
@endpush

@section('main')
    <div class="container-fluid my-4">
        <div class="container-xxl">
            <div class="row gx-0 gx-xl-3">
                <div class="col-auto">
                    @include('eshop::customer.product-collection.partials.filters')
                </div>
                <div class="col d-flex flex-column gap-3">
                    <div class="d-flex align-items-baseline">
                        <h1 class="fs-4 mb-0"><strong class="fw-normal">{{ __($collection->name) }}</strong></h1>
                        <div class="ms-3 text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                    </div>

                    <div class="d-flex gap-2">
                        @foreach($selectedManufacturers as $manufacturer)
                            <a href="{{ route('products.collections.index', array_filter([app()->getLocale(), 'manufacturer_ids' => $selectedManufacturers->toggle($manufacturer)->pluck('id')->join('-'), 'min_price' => request()->query('min_price'), 'max_price' => request()->query('max_price')])) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
                                <small class="py-1">{{ $manufacturer->name }}</small>
                                <span class="h-100" style="border-left: 1px solid #c5c5c5"></span>
                                <span class="py-1 btn-close" style="width: .25rem; height: .25rem"></span>
                            </a>
                        @endforeach
                    </div>

                    @if($products->hasPages())
                        <div class="d-flex justify-content-end">
                            {{ $products->onEachSide(1)->links('bs::pagination.paginator') }}
                        </div>
                    @endif
                    
                    @if($products->isNotEmpty())
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                            @each('eshop::customer.components.product', $products, 'product')
                        </div>
                    @else
                        <x-eshop::empty-products>
                            <a href="{{ route('products.collections.index', [app()->getLocale(), $collection->slug]) }}" class="btn btn-primary">{{ __("See all products") }}</a>
                        </x-eshop::empty-products>
                    @endif

                    @if($products->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $products->onEachSide(1)->links('bs::pagination.paginator') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
