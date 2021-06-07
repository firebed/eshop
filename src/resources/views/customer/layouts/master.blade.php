<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('meta')

    <title>{{ $title }} - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous"/>

    <link href="{{ asset('vendor/eshop/css/customer/app.css') }}" rel="stylesheet">
    @stack('header_scripts')

    <livewire:styles/>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</head>
<body @isset($bg) class="{{ $bg }}" @else style="background-color: #f1f1f1" @endisset>
<x-bs::toast-container id="toasts"/>

@include('eshop::customer.layouts.header')
@yield('main')

<x-bs::notification.toast-js/>
<x-bs::notification.dialog/>

<livewire:scripts/>
<script src="{{ asset('vendor/eshop/js/customer/app.js') }}"></script>

@stack('footer_scripts')
</body>
</html>
