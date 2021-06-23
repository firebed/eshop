@extends('eshop::customer.layouts.master', ['title' => $category->name])

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="null" :variant="null"/>

    <div class="container-fluid my-4">
        <div class="container">
            @if($category->isFile())
                <div class="row">
                    <div class="col-lg-4 col-xl-3">
                        @include('eshop::customer.category.partials.filters')
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-baseline mb-3">
                            <h1 class="fs-4 fw-normal mb-0">{{ $category->name }}</h1>
                            <div class="ms-3 text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                        </div>
                        @if($products->hasPages())
                            <div class="d-flex justify-content-end mb-3">
                                {{ $products->withQueryString()->onEachSide(1)->links('eshop::components.pagination') }}
                            </div>
                        @endif
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-4 g-3">
                            @include('eshop::customer.category.partials.products')
                        </div>
                        @if($products->hasPages())
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $products->withQueryString()->onEachSide(1)->links('eshop::components.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <h1 class="col-12 fw-500 fs-3 mb-4">{{ $category->name }}</h1>

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
