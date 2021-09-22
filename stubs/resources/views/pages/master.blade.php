@extends('layouts.master', ['title' => $title])

@push('meta')
    <link rel="canonical" href="{{ route('pages.show', [app()->getLocale(), $page]) }}">
    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('pages.show', [$locale, $page]) }}" />
    @endforeach

    <meta name='robots' content='index, follow' />

    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset(config('eshop.logo')) }}">
    <meta property="og:image:width" content="{{ config('eshop.logo_width') }}" />
    <meta property="og:image:height" content="{{ config('eshop.logo_height') }}" />
    <meta name="twitter:card" content="summary" />

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
@endpush