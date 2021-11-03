@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Pages") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 p-4 mx-auto d-grid gap-3">
        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-hover bg-white">
                <thead>
                <tr class="table-light">
                    <td>Page</td>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td><a href="{{ route('pages.edit', 'payment-methods') }}" class="text-decoration-none">{{ __("Payment methods") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'shipping-methods') }}" class="text-decoration-none">{{ __("Shipping methods") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'terms-of-service') }}" class="text-decoration-none">{{ __("Terms of service") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'data-protection') }}" class="text-decoration-none">{{ __("Data protection") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'return-policy') }}" class="text-decoration-none">{{ __("Return policy") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'cancellation-policy') }}" class="text-decoration-none">{{ __("Cancellation policy") }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('pages.edit', 'secure-policy') }}" class="text-decoration-none">{{ __("Secure transactions") }}</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
