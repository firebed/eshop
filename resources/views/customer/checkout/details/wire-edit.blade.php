@extends('eshop::customer.layouts.master', ['title' =>  __('Shipping address')])

@push('header_scripts')
    <link rel="stylesheet" href="https://unpkg.com/simplebar@5.3.3/dist/simplebar.css"/>
    <script src="https://unpkg.com/simplebar@5.3.3/dist/simplebar.min.js"></script>
@endpush

@section('main')
    <div class="container-fluid py-5">
        <div class="container">

            @if(session()->has('guest-cart-merged-with-user-cart'))
                <div class="alert bg-teal-400 alert-dismissible fade show" role="alert">
                    <span>{{ __("Your current cart was merged with the cart from your previous login.") }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

                @if($errors->isNotEmpty())
                    <x-bs::alert type="danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-bs::alert>
                @endif

            <div class="d-grid gap-3">
                <h1 class="fs-3 fw-normal">{{ __('Shipping address') }}</h1>

                <livewire:customer.checkout.edit-checkout-details />
            </div>
        </div>
    </div>
@endsection
