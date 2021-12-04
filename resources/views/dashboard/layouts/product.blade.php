@extends('eshop::dashboard.layouts.master')

@section('header')
    <a href="{{ route('products.index') }}" class="text-dark text-decoration-none">{{ __("Products") }}</a>
@endsection

@section('main')
    <div class="col-12 p-4">
        <div class="col-12 col-xxl-9 mx-auto mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                @includeWhen(isset($product), 'eshop::dashboard.product.partials.product-header')
                
                @yield('actions')
            </div>
        </div>

        <div class="col-12 col-xxl-9 mx-auto">
            @yield('content')
        </div>
    </div>
@endsection