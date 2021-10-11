@php
    $title = __("Order tracking");
    $description = "Κάντε αναζήτηση και δείτε την εξέλιξη της παραγγελίας σας αν πάσα στιγμή.";
@endphp

@extends('layouts.master', ['title' => $title])

@push('meta')
    <link rel="canonical" href="{{ route('order-tracking.index', app()->getLocale()) }}">

    @foreach(array_keys(config('eshop.locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ route('order-tracking.index', $locale) }}"/>
    @endforeach

    <meta name="description" content="{{ $description }}">

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset(config('eshop.logo')) }}">
    <meta name="twitter:card" content="summary"/>

    <meta name='robots' content='index, follow'/>
@endpush

@section('main')
    <div class="container-fluid my-4">
        <div class="container-xxl">
            <h1 class="mb-4 fs-4">{{ $title }}</h1>
            
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                <div class="col">
                    <x-bs::card class="h-100">
                        <x-bs::card.body>
                            <form action="{{ route('order-tracking.search_by_voucher', app()->getLocale()) }}" method="post" class="d-grid gap-3">
                                @csrf

                                <div><strong>{{ __("Search order by tracking code") }}</strong></div>

                                <div class="small text-secondary">{{ __("If you have the shipping code please enter it in the field below and click search.") }}</div>

                                <x-bs::input.group for="voucher" label="{{ __('Voucher') }}">
                                    <x-bs::input.text id="voucher" name="voucher" error="voucher"/>
                                </x-bs::input.group>

                                <div>
                                    <x-bs::button.primary type="submit">{{ __("Search") }}</x-bs::button.primary>
                                </div>
                            </form>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>

                <div class="col">
                    <x-bs::card class="h-100">
                        <x-bs::card.body>
                            <form action="{{ route('order-tracking.search_by_id', app()->getLocale()) }}" method="post" class="d-grid gap-3">
                                @csrf

                                <div><strong>{{ __("Search order by email") }}</strong></div>

                                <div class="small text-secondary">{{ __("Enter below the order code and the email you used during the order and click search.") }}</div>

                                <div class="row g-3">
                                    <x-bs::input.group for="id" label="{{ __('Order id') }}" class="col-12 col-sm-4">
                                        <x-bs::input.text id="id" name="id" error="id"/>
                                    </x-bs::input.group>

                                    <x-bs::input.group for="email" label="{{ __('Email') }}" class="col-12 col-sm-8">
                                        <x-bs::input.text id="email" name="email" error="email"/>
                                    </x-bs::input.group>
                                </div>

                                <div>
                                    <x-bs::button.primary type="submit">{{ __("Search") }}</x-bs::button.primary>
                                </div>
                            </form>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>
            </div>
        </div>
    </div>
@endsection
