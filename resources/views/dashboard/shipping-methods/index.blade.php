@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-5 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ __("Shipping methods") }}</h1>

            <a href="{{ route('shipping-methods.create') }}" class="btn btn-primary rounded-circle shadow-sm">
                <em class="fas fa-plus"></em>
            </a>
        </div>

        <div class="list-group shadow-sm">
            @foreach($shippingMethods as $method)
                <div class="py-0 list-group-item list-group-item-action d-flex justify-content-between">
                    <a href="{{ route('shipping-methods.show', $method) }}" class="py-3 w-100 text-decoration-none text-dark">
                        {{ $method->name }}
                    </a>

                    <a href="{{ route('shipping-methods.edit', $method) }}" class="py-3 ps-3 border-start text-decoration-none"><small class="fas fa-pen"></small></a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
