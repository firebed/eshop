@extends('eshop::customer.layouts.master', ['title' => $title])

@push('meta')
    <link rel="canonical" href="{{ route('pages.show', [app()->getLocale(), $page]) }}">
    @foreach(array_keys(eshop('locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('pages.show', [$locale, $page]) }}" />
    @endforeach

    <meta name='robots' content='index, follow' />

    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('pages.show', [app()->getLocale(), $page]) }}">
    @include('eshop::customer.layouts.partials.meta-logo')
    <meta name="twitter:card" content="summary" />

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
@endpush