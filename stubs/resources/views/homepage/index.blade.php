@extends('layouts.master', ['title' => __('company.seo.title')])

@push('meta')
    <link rel="canonical" href="{{ route('home', app()->getLocale()) }}">
    <meta name="description" content="{{ __('company.seo.description') }}">

    <script type="application/ld+json">{!! $webSite !!}</script>
    <script type="application/ld+json">{!! $webPage->handle(config('app.name'), __('company.seo.description')) !!}</script>
    <script type="application/ld+json">{!! $organization !!}</script>

    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ __('company.seo.description') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset(config('eshop.logo')) }}">
@endpush

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.min.css"/>
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.js"></script>
@endpush

@section('main')
    <div class="container-fluid bg-white">
        <div class="container-xxl">
            <div class="row g-0">
                <div class="col-3 d-none d-lg-block">
                    <div class="list-group border-0 rounded-0">
{{--                        List of preferred categories--}}
                    </div>
                </div>
                <div class="col pt-3 ps-lg-5 pt-lg-5">
                    @include('homepage.partials.carousel')
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-white">
        <div class="container-xxl">
            <div class="fw-bold py-5 vstack gap-3">
                <h2 class="mx-auto">Trending</h2>
                <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>

                <div class="d-flex flex-wrap gap-3 mx-auto">
{{--                    <x-bs::button.primary class="rounded-pill px-3 btn-sm">Category</x-bs::button.primary>--}}
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-4">
                @foreach ($trending as $product)
                    <div class="col">
                        <div class="vstack h-100 gap-2">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                                @if($src = $product->image?->url('sm'))
                                    <img src="{{ $src }}" alt="" class="rounded">
                                @endif
                            </a>

                            <h2 class="fs-6"><a href="{{ productRoute($product) }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></h2>
                            <div class="mt-auto"><strong>{{ format_currency($product->net_value) }}</strong></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 pb-5 bg-white">
        <div class="container-xxl">
            <div class="fw-bold py-5 vstack gap-3">
                <h2 class="mx-auto">Best Sellers</h2>
                <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>
            </div>

            <x-bs::slider slides="2" slides-lg="3" slides-xl="5" interval="5000" class="gx-4">
                @foreach($popular as $product)
                    <x-bs::slider.item {{--class="w-md-50 w-lg-1/3 w-xl-25 pb-1"--}}>
                        <div class="vstack h-100 gap-2 justify-content-between">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                                @if($src = $product->image?->url('sm'))
                                    <img class="rounded" src="{{ $src }}" alt="">
                                @endif
                            </a>

                            <h2 class="fs-6"><a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></h2>
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="text-decoration-none text-dark mt-auto"><strong>{{ format_currency($product->net_value) }}</strong></a>
                        </div>
                    </x-bs::slider.item>
                @endforeach

                <x-bs::slider.nav/>
            </x-bs::slider>
        </div>
    </div>
@endsection