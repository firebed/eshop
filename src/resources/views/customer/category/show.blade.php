@extends('customer.layouts.master', ['title' => $category->name])

@section('main')

    <x-category-breadcrumb :category="$category"/>

    <div class="container-fluid">
        <div class="container">
            <div class="row">
                @if($category->isFile())
                    <div class="col-lg-4 col-xl-3">
                        @include('customer.category.partials.filters')
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-baseline mb-3">
                            <h1 class="fs-4 fw-normal mb-0">{{ $category->name }}</h1>
                            <div class="ms-3 text-secondary">({{ $products->total() }} {{ __("products") }})</div>
                        </div>
                        @if($products->hasPages())
                            <div class="d-flex justify-content-end mb-3">
                                {{ $products->withQueryString()->onEachSide(1)->links('components.modern-paginator') }}
                            </div>
                        @endif
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-4 g-3">
                            @include('customer.category.partials.products')
                        </div>
                        @if($products->hasPages())
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $products->withQueryString()->onEachSide(1)->links('components.modern-paginator') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
