@php($selectedManufacturersTitle = (isset($filters['m']) && $filters['m']->isNotEmpty() ? ' ' . $filters['m']->pluck('name')->join(', ') : '' ))
@php($selectedOptionsTitle = (isset($filters['c']) && $filters['c']->isNotEmpty() ? ' ' . $filters['c']->pluck('name')->join(', ') : '' ))

@php($t = implode(' ', array_filter([trim($selectedManufacturersTitle), trim($selectedOptionsTitle)])))

@php($title = implode(' ', array_filter([$category->seo->title ?? $category->name ?? "", $t])))

{{--@php($description = $category?->seo->description ?? "")--}}
@if($category->isFile())
    @php($description = "Διαλέξτε ανάμεσα σε " . $products->total() . " " . $category->name . ($t ? " $t" : "") . " το προϊόν που κάνει για σας στην καλύτερη τιμή. Αγόρασε με ασφάλεια μέσω του " . config('app.name') . "!")
@else
    @php($description = "Δείτε όλα τα προϊόντα της κατηγορίας $category->name, συγκρίνετε τιμές & αγοράστε το προϊόν που σας ενδιαφέρει από την κατηγορία $category->name.")
@endif

@extends('eshop::customer.layouts.master', ['title' => $title])

@push('meta')
    @foreach(array_keys(eshop('locales')) as $locale)
        @if($category->isFile())
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ categoryRoute($category, $filters['m'], $filters['c'], locale: $locale, sort: $filters['sort']) . ($products->currentPage() > 1 ? '?page=' . $products->currentPage() : '') }}"/>
        @else
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ categoryRoute($category, locale: $locale) }}"/>
        @endif
    @endforeach

    @if(filled($description))
        <meta name="description" content="{{ $description }}">
    @endif

    @if($category->isFile())
        @if(!$products->hasPages() || $products->onFirstPage())
            <link rel="canonical" href="{{ $canonical = categoryRoute($category, $filters['m'], $filters['c'], sort: $filters['sort']) }}">
        @else
            <link rel="canonical" href="{{ $canonical = $products->url($products->currentPage()) }}">
        @endif

        @if($products->currentPage() > 1)
            @push('preload')
                <link rel="prefetch" href="{{ $products->withQueryString()->previousPageUrl() }}">
            @endpush

            @if($products->currentPage() == 2)
                <link rel="prev" href="{{ categoryRoute($category, sort: $filters['sort']) }}">
            @elseif($products->currentPage() > 2)
                <link rel="prev" href="{{ $products->withQueryString()->previousPageUrl() }}">
            @endif
        @endif

        @if($products->hasMorePages())
            @push('preload')
                <link rel="prefetch" href="{{ $products->withQueryString()->nextPageUrl() }}">
            @endpush
            <link rel="next" href="{{ $products->withQueryString()->nextPageUrl() }}">
        @endif
    @else
        <link rel="canonical" href="{{ $canonical = categoryRoute($category) }}">
    @endif

    @if($category->isFile())
        <meta name='robots' content='{{ $products->isEmpty() || (count($filters['m']) + count($filters['c']) >= 4) ? 'noindex' : 'index' }}, follow'/>
    @else
        <meta name='robots' content='{{ $children->isEmpty() ? 'noindex' : 'index' }}, follow'/>
    @endif

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
    <script type="application/ld+json">{!! schema()->breadcrumb($category) !!}</script>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @if(filled($description))
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    @if($category->image)
        <meta property="og:image" content="{{ $category->image->url() }}">
    @endif
    <meta name="twitter:card" content="summary"/>
@endpush

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="null"/>

    <main class="container-fluid my-4">
        <div class="container-xxl">
            @if($category->isFile())
                <div class="row gx-0 gx-xl-3">
                    <section class="col d-flex flex-column gap-3 order-1">
                        <div class="d-flex flex-wrap align-items-baseline gap-3">
                            <h1 class="fs-4 mb-0 fw-normal">{{ $category->name }}</h1>
                            <div class="text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                            @can('Manage categories')
                                <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none ms-auto"><em class="fas fa-edit"></em> {{ __("Edit") }}</a>
                            @endcan
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($filters['m'] as $m)
                                <a href="{{ categoryRoute($category, $filters['m']->toggle($m), $filters['c'], $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
                                    <small class="py-1">{{ $m->name }}</small>
                                    <span class="h-100" style="border-left: 1px solid #c5c5c5"></span>
                                    <span class="py-1 btn-close" style="width: .25rem; height: .25rem"></span>
                                </a>
                            @endforeach

                            @foreach($filters['c'] as $c)
                                <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($c), $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
                                    <small class="py-1">{{ $category->properties->find($c->category_property_id)->choices->find($c->id)->name }}</small>
                                    <span class="h-100" style="border-left: 1px solid #c5c5c5"></span>
                                    <span class="py-1 btn-close" style="width: .25rem; height: .25rem"></span>
                                </a>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="dropdown">
                                <a class="btn btn-white dropdown-toggle" href="#" role="button" id="sort-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ match($filters['sort']) {'price' => 'Αύξουσα τιμή', 'price-desc' => 'Φθίνουσα τιμή', default => 'Περιγραφή'} }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="sort-dropdown">
                                    <li><a class="dropdown-item" href="{{ categoryRoute($category, $filters['m'], $filters['c'], $filters['min_price'], $filters['max_price'], sort: 'name') }}">Περιγραφή</a></li>
                                    <li><a class="dropdown-item" href="{{ categoryRoute($category, $filters['m'], $filters['c'], $filters['min_price'], $filters['max_price'], sort: 'price') }}">Αύξουσα τιμή</a></li>
                                    <li><a class="dropdown-item" href="{{ categoryRoute($category, $filters['m'], $filters['c'], $filters['min_price'], $filters['max_price'], sort: 'price-desc') }}">Φθίνουσα τιμής</a></li>
                                </ul>
                            </div>

                            @if($products->hasPages())
                                {{ $products->withQueryString()->onEachSide(1)->links('bs::pagination.paginator') }}
                            @endif
                        </div>

                        @if($products->isNotEmpty())
                            <ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 list-unstyled">
                                @each('eshop::customer.components.product', $products, 'product')
                            </ul>
                        @else
                            <x-eshop::empty-products>
                                <a href="{{ categoryRoute($category, sort: $filters['sort']) }}" class="btn btn-primary">{{ __("See all products in the category") }}</a>
                            </x-eshop::empty-products>
                        @endif

                        @if($products->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $products->withQueryString()->onEachSide(1)->links('bs::pagination.paginator') }}
                            </div>
                        @endif
                    </section>

                    <section class="col-auto order-0">
                        @include('eshop::customer.category.partials.filters')
                    </section>
                </div>
            @else
                @include('eshop::customer.category.partials.category-folder')
            @endif
        </div>
    </main>
@endsection
