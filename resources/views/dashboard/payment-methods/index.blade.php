@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-5 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ __("Payment methods") }}</h1>

            <a href="{{ route('payment-methods.create') }}" class="btn btn-primary rounded-circle shadow-sm">
                <em class="fas fa-plus"></em>
            </a>
        </div>

        <div class="list-group shadow-sm">
            @foreach($paymentMethods as $method)
                <a href="{{ route('payment-methods.edit', $method) }}" class="py-3 list-group-item list-group-item-action">
                    {{ __("eshop::payment.$method->name") }}
                </a>
            @endforeach
        </div>
    </div>
@endsection
