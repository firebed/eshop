<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow"/>

    @stack('meta')

    <title>{{ $title }} - {{ config('app.name') }}</title>

    @includeIf('partials.favicon')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous"/>

    <link rel="search" type="application/opensearchdescription+xml" href="{{ asset('opensearch.xml') }}" title="Product search">

    <link href="{{ mix('css/customer/app.css') }}" rel="stylesheet">

    @stack('header_scripts')

    <livewire:styles/>
    <script defer src="https://unpkg.com/alpinejs@3.2.1/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-eshop::google-analytics/>
</head>
<body @isset($bg) class="{{ $bg }}" @else style="background-color: #f1f1f1" @endisset>

<x-bs::toast-container id="toasts"/>

@include('layouts.header')
@yield('main')
@include('layouts.footer')

<x-bs::notification.toast-js/>
<x-bs::notification.dialog/>

<livewire:scripts/>

<script src="{{ mix('js/customer/app.js') }}"></script>
@stack('footer_scripts')
</body>
</html>
