@extends('eshop::customer.layouts.master', ['title' => 'Προσφορές - Όλες οι κατηγορίες'])

@push('meta')
    <meta name="description" content="Δες όλες τις προσφορές των προϊόντων για όλες της κατηγορίες στην καλύτερη τιμή!">

    <script type="application/ld+json">{!! $webPage->handle('Προσφορές - Όλες οι κατηγορίες', "Δες όλες τις προσφορές των προϊόντων για όλες της κατηγορίες στην καλύτερη τιμή!") !!}</script>

    <meta property="og:title" content="Προσφορές - Όλες οι κατηγορίες">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="Δες όλες τις προσφορές των προϊόντων για όλες της κατηγορίες στην καλύτερη τιμή!">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset(config('eshop.logo')) }}">
@endpush

@push('meta')
    @isset($products)
        @if($products->onFirstPage())
            <link rel="canonical" href="{{ route('customer.products.offers.index', app()->getLocale()) }}">
        @else
            <link rel="canonical" href="{{ $products->url($products->currentPage()) }}">
        @endif

        @if($products->currentPage() == 2)
            <link rel="prev" href="{{ route('customer.products.offers.index', app()->getLocale()) }}">
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
                    @include('eshop::customer.product-offers.partials.filters')
                </div>
                <div class="col d-flex flex-column gap-3">
                    <div class="d-flex align-items-baseline">
                        <h1 class="fs-4 fw-normal mb-0">{{ __("Offers") }}</h1>
                        <div class="ms-3 text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                    </div>

                    <div class="d-flex gap-2">
                        @foreach($selectedManufacturers as $manufacturer)
                            <a href="{{ route('customer.products.offers.index', ['$manufacturer_ids' => $selectedManufacturers->toggle($manufacturer), request()->query('min_price'), request()->query('max_price')]) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
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

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                        @include('eshop::customer.product-offers.partials.products')
                    </div>

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