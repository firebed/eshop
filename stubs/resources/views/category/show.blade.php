@php($selectedManufacturersTitle = (isset($filters['m']) && $filters['m']->isNotEmpty() ? ' ' . $filters['m']->pluck('name')->join(', ') : '' ))
@php($selectedOptionsTitle = (isset($filters['c']) && $filters['c']->isNotEmpty() ? ' ' . $filters['c']->pluck('name')->join(', ') : '' ))

@php($title = implode(' ', array_filter([$category->seo->title ?? $category->name ?? "", $selectedManufacturersTitle, $selectedOptionsTitle])))

@php($description = $category?->seo->description ?? "")

@extends('layouts.master', ['title' => $title])

@push('meta')
    @foreach(array_keys(config('eshop.locales')) as $locale)
        @if($category->isFile())
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ categoryRoute($category, $filters['m'], $filters['c'], locale: $locale) . ($products->currentPage() > 1 ? '?page=' . $products->currentPage() : '') }}"/>
        @else
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ categoryRoute($category, locale: $locale) . ($products->currentPage() > 1 ? '?page=' . $products->currentPage() : '') }}"/>
        @endif
    @endforeach

    @if(!empty($category->seo->description))
        <meta name="description" content="{{ $description }}">
    @endif

    @if($category->isFile())
        @if(!$products->hasPages() || $products->onFirstPage())
            <link rel="canonical" href="{{ $canonical = categoryRoute($category, $filters['m'], $filters['c']) }}">1
        @else
            <link rel="canonical" href="{{ $canonical = $products->url($products->currentPage()) }}">
        @endif

        @if($products->currentPage() == 2)
            <link rel="prev" href="{{ categoryRoute($category) }}">
        @elseif($products->currentPage() > 2)
            <link rel="prev" href="{{ $products->previousPageUrl() }}">
        @endif

        @if($products->hasMorePages())
            <link rel="next" href="{{ $products->nextPageUrl() }}">
        @endif
    @else
        <link rel="canonical" href="{{ $canonical = categoryRoute($category) }}">
    @endif

    @if($category->isFile())
        <meta name='robots' content='{{ $products->isEmpty() ? 'noindex' : 'index' }}, follow'/>
    @else
        <meta name='robots' content='{{ $children->isEmpty() ? 'noindex' : 'index' }}, follow'/>
    @endif

    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>
    <script type="application/ld+json">{!! schema()->breadcrumb($category) !!}</script>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @if(!empty($description))
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

    <div class="container-fluid my-4">
        <div class="container-xxl">
            @if($category->isFile())
                <div class="row gx-0 gx-xl-3">
                    <div class="col-auto">
                        @include('category.partials.filters')
                    </div>
                    <div class="col d-flex flex-column gap-3">
                        <div class="d-flex align-items-baseline">
                            <h1 class="fs-4 mb-0"><strong class="fw-normal">{{ $category->name }}</strong></h1>
                            <div class="ms-3 text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($filters['m'] as $m)
                                <a href="{{ categoryRoute($category, $filters['m']->toggle($m), $filters['c'], $filters['min_price'], $filters['max_price']) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
                                    <small class="py-1">{{ $m->name }}</small>
                                    <span class="h-100" style="border-left: 1px solid #c5c5c5"></span>
                                    <span class="py-1 btn-close" style="width: .25rem; height: .25rem"></span>
                                </a>
                            @endforeach

                            @foreach($filters['c'] as $c)
                                <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($c), $filters['min_price'], $filters['max_price']) }}" class="btn btn-smoke px-2 py-0 d-flex gap-2 align-items-center">
                                    <small class="py-1">{{ $category->properties->find($c->category_property_id)->choices->find($c->id)->name }}</small>
                                    <span class="h-100" style="border-left: 1px solid #c5c5c5"></span>
                                    <span class="py-1 btn-close" style="width: .25rem; height: .25rem"></span>
                                </a>
                            @endforeach
                        </div>

                        @if($products->hasPages())
                            <div class="d-flex justify-content-end">
                                {{ $products->onEachSide(1)->links('bs::pagination.paginator') }}
                            </div>
                        @endif

                        @if($products->isNotEmpty())
                            @include('category.partials.products')
                        @else
                            @include('category.partials.empty-products')
                        @endif

                        @if($products->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $products->onEachSide(1)->links('bs::pagination.paginator') }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <h1 class="col-12 fs-3 mb-4"><strong class="fw-500">{{ $category->name }}</strong></h1>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-4">
                    @foreach($children as $child)
                        <div class="col">
                            <div class="p-3 h-100 bg-white d-flex flex-column gap-3 rounded border">
                                <a href="{{ categoryRoute($child) }}" class="ratio ratio-4x3">
                                    @if($child->image && $src = $child->image->url('sm'))
                                        <img src="{{ $src }}" class="img-top rounded" alt="{{ $child->name }}">
                                    @endif
                                </a>

                                <a href="{{ categoryRoute($child) }}" class="text-dark text-hover-underline fw-500">{{ $child->name }}</a>

                                @if($child->children->isNotEmpty())
                                    <div class="mt-auto">
                                        @foreach($child->children as $promoted)
                                            <a href="{{ categoryRoute($promoted) }}" class="text-secondary text-hover-underline">{{ $promoted->name }}</a>@unless($loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
