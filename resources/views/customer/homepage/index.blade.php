@extends('eshop::customer.layouts.master', ['title' => __('company.seo.title')])

@push('meta')
    @if(Route::currentRouteName() === 'landing_page')
        <link rel="canonical" href="{{ $canonical = url('/') }}">
    @else
        <link rel="canonical" href="{{ $canonical = route('home', app()->getLocale()) }}">
    @endif

    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}"/>
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('home', $locale) }}"/>
    @endforeach

    <script type="application/ld+json">{!! schema()->webSite('website') !!}</script>
    <script type="application/ld+json">{!! schema()->webPage(config('app.name'), __('company.seo.description')) !!}</script>
    <script type="application/ld+json">{!! schema()->organization() !!}</script>

    <meta name="description" content="{{ __('company.seo.description') }}">
    <meta property="og:locale" content="{{ app()->getLocale() . '_' . config('eshop.countries')[app()->getLocale()] }}"/>
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ __('company.seo.description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ asset('images/logo.webp') }}">
    <meta name="twitter:card" content="summary"/>

    <meta name='robots' content='index, follow'/>
@endpush

@push('header_scripts')
    <link rel="stylesheet" href="{{ mix('dist/keen-slider.css') }}"/>
@endpush

@push('header_scripts')
    <script src="{{ asset('keen/keen-slider.js') }}"></script>
@endpush

@section('main')
    @include('eshop::customer.homepage.main')
@endsection
