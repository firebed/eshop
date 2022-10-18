@extends('eshop::dashboard.layouts.master')

@push('header_scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush

@section('header')
    <div class="fs-5 fw-500">Δημιουργία Voucher</div>
@endsection

@section('main')
    <div class="col-12 p-4">
        <form action="{{ route('vouchers.store') }}" method="post">
            @csrf

            <div id="forms-modal" class="modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div x-data="{ cart: null }" x-on:show-cart.window="cart = $event.detail" class="modal-content shadow">
                        <x-bs::modal.header>Επεξεργασία voucher</x-bs::modal.header>
                        <x-bs::modal.body>
                            @foreach($carts as $cart_id => $cart)
                                <div x-show="cart == @js($cart_id)" x-cloak id="cart-{{ $cart_id }}">
                                    @includeWhen($cart->shippingMethod->name === 'ACS Courier', 'eshop::dashboard.voucher.partials.acs-form')
                                    @includeWhen($cart->shippingMethod->name === 'SpeedEx', 'eshop::dashboard.voucher.partials.speedex-form')
                                </div>
                            @endforeach
                        </x-bs::modal.body>
                    </div>
                </div>
            </div>

            <div class="table-responsive bg-white shadow-sm rounded">
                @include('eshop::dashboard.voucher.partials.orders-voucher-table')
            </div>
        </form>
        
        @include('eshop::dashboard.voucher.modals.voucher-address-modal')
    </div>
@endsection
