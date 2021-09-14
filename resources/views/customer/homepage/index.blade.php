@extends('eshop::customer.layouts.master', ['title' => __('company.job')])

@php($description = "Χιλιάδες προϊόντα σε βρεφικά ρούχα και παιδικά ρούχα και αξεσουάρ για αγόρια και κορίτσια για όλες τις ηλικίες σας περιμένουν στο κατάστημα Minimoda με μειωμένες τιμές.")

@push('meta')
    <link rel="canonical" href="{{ route('home', app()->getLocale()) }}">
    <meta name="description" content="{{ $description }}">

    <script type="application/ld+json">{!! $webSite !!}</script>
    <script type="application/ld+json">{!! $webPage->handle(config('app.name'), $description) !!}</script>
    <script type="application/ld+json">{!! $organization !!}</script>

    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('/storage/images/alt_logo.png') }}">
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
                        <a href="#" class="list-group-item list-group-item-action fw-500">Βρεφικά Ρούχα</a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Ολόσωμες Φόρμες</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Σετ για νεογέννητα</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Κορμάκια</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Πιτζάμες</span></a>

                        <a href="#" class="list-group-item list-group-item-action fw-500">Παδικά Ρούχα</a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Σετ φόρμες</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Πουκάμισα</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Παντελόνια - Σορτς</span></a>
                        <a href="#" class="list-group-item list-group-item-action"><span class="ps-3">Πιτζάμες</span></a>

                        <a href="#" class="d-none d-xl-block list-group-item list-group-item-action fw-500">Αξεσουάρ</a>
                        <a href="#" class="d-none d-xl-block list-group-item list-group-item-action"><span class="ps-3">Σαλιάρες</span></a>
                        <a href="#" class="d-none d-xl-block list-group-item list-group-item-action"><span class="ps-3">Κουβέρτες</span></a>
                        <a href="#" class="d-none d-xl-block list-group-item list-group-item-action"><span class="ps-3">Παπούτσια</span></a>
                    </div>
                </div>
                <div class="col pt-3 ps-lg-5 pt-lg-5">
                    @include('eshop::customer.homepage.partials.carousel')
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-white">
        <div class="container-xxl">
            <div class="fw-bold py-5 vstack gap-3">
                <h2 class="mx-auto">Trending</h2>
                <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>

                <div class="d-flex flex-wrap gap-3 mx-auto">
                    <x-bs::button.primary class="rounded-pill px-3 btn-sm">Αγόρι</x-bs::button.primary>
                    <x-bs::button.primary class="rounded-pill px-3 btn-sm">Κορίτσι</x-bs::button.primary>
                    <x-bs::button.primary class="rounded-pill px-3 btn-sm">Σαλοπέτα</x-bs::button.primary>
                    <x-bs::button.primary class="rounded-pill px-3 btn-sm">Σετ για νεογέννητα</x-bs::button.primary>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-4">
                @foreach ($trending as $product)
                    <div class="col">
                        <div class="vstack h-100 gap-2">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                                @if($src = $product->image?->url('sm'))
                                    <img src="{{ $src }}" alt="" class="rounded">
                                @endif
                            </a>

                            <h2 class="fs-6"><a href="{{ productRoute($product) }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></h2>
                            <div class="mt-auto"><strong>{{ format_currency($product->net_value) }}</strong></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 pb-5 bg-white">
        <div class="container-xxl">
            <div class="fw-bold py-5 vstack gap-3">
                <h2 class="mx-auto">Best Sellers</h2>
                <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>
            </div>

            <x-bs::slider slides="2" slides-lg="3" slides-xl="5" interval="5000" class="gx-4">
                @foreach($popular as $product)
                    <x-bs::slider.item {{--class="w-md-50 w-lg-1/3 w-xl-25 pb-1"--}}>
                        <div class="vstack h-100 gap-2 justify-content-between">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                                @if($src = $product->image?->url('sm'))
                                    <img class="rounded" src="{{ $src }}" alt="">
                                @endif
                            </a>

                            <h2 class="fs-6"><a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></h2>
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="text-decoration-none text-dark mt-auto"><strong>{{ format_currency($product->price) }}</strong></a>
                        </div>
                    </x-bs::slider.item>
                @endforeach

                <x-bs::slider.nav/>
            </x-bs::slider>
        </div>
    </div>
@endsection
