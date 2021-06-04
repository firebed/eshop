<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('meta')

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous"/>
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
    @stack('header_link')
    @stack('header_scripts')
    @livewireStyles
</head>
<body class="container-fluid bg-light">
<div class="row">
    @include('dashboard.partials.dashboard-navigation')
    <div class="col">
        <main class="row">
            {{ $slot }}
        </main>
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
@stack('footer_scripts')
@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
</body>
</html>
