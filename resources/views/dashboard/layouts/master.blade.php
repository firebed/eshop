<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('meta')

    <title>{{ config('app.name') }}</title>

    @includeIf('partials.favicon')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous"/>

    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

    <link href="{{ mix('dist/dashboard.css') }}" rel="stylesheet">
    @stack('header_scripts')
    @livewireStyles

    <script defer src="{{ mix('dist/dashboard.js') }}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.2.1/dist/cdn.min.js"></script>

    <x-eshop::google-analytics/>
</head>
<body class="container-fluid bg-light">

<div class="row flex-xl-nowrap">
    @include('eshop::dashboard.layouts.navigation')

    <div class="col">
        <main class="row">
            <div class="d-flex col-12 bg-white shadow-sm sticky-xl-top justify-content-between align-items-center px-4" style="height: 3.5rem">
                <div class="d-flex gap-2 align-items-baseline">
                    <a x-data x-on:click.prevent="$dispatch('toggle-collapse')" class="text-gray-600" href="#">
                        <em class="fa fa-bars" style="font-size: 1.1rem"></em>
                    </a>
                    
                    @yield('header')
                </div>

                <div class="d-none d-sm-flex gap-2 align-items-center">
                    <em class="fas fa-user text-gray-500"></em>
                    <div class="text-nowrap">{{ auth()->user()?->fullName }}</div>
                </div>
            </div>

            @yield('main')
        </main>
    </div>
</div>

<x-bs::notification.toast/>
<x-bs::notification.dialog/>

<a href="{{ route('home', app()->getLocale()) }}" class="btn btn-primary rounded-circle shadow d-flex justify-content-center align-items-center" style="position:fixed; bottom: 10px; width: 48px; height: 48px; left: 10px; z-index: 10000"><em class="fas fa-store"></em></a>

@include('eshop::dashboard.layouts.toasts')

@stack('footer_scripts')
@livewireScripts
</body>
</html>
