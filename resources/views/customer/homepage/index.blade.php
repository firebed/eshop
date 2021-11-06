@extends('eshop::customer.layouts.master', ['title' => __('company.seo.title')])

@push('meta')
    @if(Route::currentRouteName() === 'landing_page')
        <link rel="canonical" href="{{ $canonical = url('/') }}">
    @else
        <link rel="canonical" href="{{ $canonical = route('home', app()->getLocale()) }}">
    @endif

    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}" />
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('home', $locale) }}" />
    @endforeach

    <script type="application/ld+json">{!! schema()->webSite('website') !!}</script>
    <script type="application/ld+json">{!! schema()->webPage(config('app.name'), __('company.seo.description')) !!}</script>
    <script type="application/ld+json">{!! schema()->organization() !!}</script>

    <meta name="description" content="{{ __('company.seo.description') }}">
    <meta property="og:locale" content="{{ app()->getLocale() . '_' . config('eshop.countries')[app()->getLocale()] }}" />
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ __('company.seo.description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    @include('eshop::customer.layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary" />

    <meta name='robots' content='index, follow' />
@endpush

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.min.css"/>
@endpush

@push('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/keen-slider@latest/keen-slider.js"></script>
@endpush

@section('main')
    <section id="homepage-main-section" class="container-fluid py-4">
        <div class="container-xxl">
            <div class="row g-0">
                <div class="col-3 d-none d-lg-block">
                    <div class="list-group border-0 rounded-0">
                        @includeIf('eshop::customer.homepage.partials.categories-list')
                    </div>
                </div>
                <div id="gallery" class="col">
                    <x-eshop-gallery/>
                </div>
            </div>
        </div>
    </section>

    <x-eshop-trending-products/>
    <x-eshop-bestsellers/>
@endsection
