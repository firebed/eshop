@extends('eshop::customer.layouts.master', ['title' => $category->name])

@section('main')
    <x-eshop-category-breadcrumb :category="$category" :product="null" :variant="null"/>

    <div class="container-fluid my-4">
        <div class="container-xxl">
            @if($category->isFile())
                <div class="row gx-0 gx-xl-3">
                    <div class="col-auto">
                        @include('eshop::customer.category.partials.filters')
                    </div>
                    <div class="col d-flex flex-column gap-3">
                        <div class="d-flex align-items-baseline">
                            <h1 class="fs-4 fw-normal mb-0">{{ $category->name }}</h1>
                            <div class="ms-3 text-secondary">(@choice("eshop::product.products_count", $products->total(), ['count' => $products->total()]))</div>
                        </div>

                        <div class="d-flex gap-2">
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

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                            @include('eshop::customer.category.partials.products')
                        </div>

                        @if($products->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $products->onEachSide(1)->links('bs::pagination.paginator') }}
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
