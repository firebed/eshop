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
                        @includeIf('homepage.partials.categories-list')
                    </div>
                </div>
                <div class="col pt-3 ps-lg-5 pt-lg-5">
                    @include('homepage.partials.carousel')
                </div>
            </div>
        </div>
    </div>

    @includeIf('homepage.partials.trending-products')
    @includeIf('homepage.partials.best-sellers')
@endsection
