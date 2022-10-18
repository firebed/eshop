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
    <div class="col-12">
        <div class="row">
            <div x-data="{ progress: @js(array_fill(0, count($carts), false)) }" class="col-2 d-flex flex-column bg-white shadow-sm">
                <div class="progress my-2">
                    <template x-for="(p, i) in progress">
                        <div class="progress-bar" :class="{ 'bg-danger': !p, 'bg-success': p }" role="progressbar" :style="`width: ${100/progress.length}%`"></div>
                    </template>
                </div>

                <div class="d-flex flex-column overflow-y-auto scrollbar" style="height: calc(100vh - 6rem); z-index: 0">
                    @foreach($carts as $cart_id => $cart)
                        <a x-data="{ 
                            success: @js(filled(old('carts.'.$cart_id.'.station'))),
                            load: function() {
                                this.loading = true
                                axios.post(@js(route('vouchers.search-stations')), {
                                    street:    @js($cart->shippingAddress->street),
                                    street_no: @js($cart->shippingAddress->street_no),
                                    postcode:  @js($cart->shippingAddress->postcode),
                                }).then(r => {
                                    this.setStation(r.data.id, r.data.name)
                                    progress[{{ $loop->index }}] = true
                                })
                                .catch(() => this.setStation('', ''))
                                .finally(() => this.loading = false)                            
                            },
                            setStation: function(id, name) {
                                stationId = document.getElementById('cart-{{ $cart_id }}-station-id')
                                stationName = document.getElementById('cart-{{ $cart_id }}-station')
                                stationId.value = id
                                stationName.value = name
                                if (id != '') {
                                    this.success = true
                                    stationName.classList.remove('is-invalid')
                                    stationId.classList.remove('is-invalid')
                                } else {
                                    stationName.classList.add('is-invalid')
                                    stationId.classList.add('is-invalid')
                                }                            
                            }
                           }"
                           @if(empty(old("carts.$cart_id.station"))) x-init="load()" @endif
                           x-on:set-station.window="
                            if ($event.detail.cart == @js($cart_id)) {
                                setStation($event.detail.id, $event.detail.name)
                            }
                           "
                           href="#cart-{{ $cart_id }}" class="row small border-top py-3 text-decoration-none text-dark align-items-center">
                            <div class="col-2">
                                <div x-show="loading" x-cloak class="spinner-border text-gray-500" role="status"></div>
                                <em x-show="!loading && success" x-cloak class="fa fa-check-circle text-success fa-2x"></em>
                                <em x-show="!loading && !success" x-cloak class="fa fa-times-circle text-danger fa-2x"></em>
                            </div>

                            <div class="col-10 d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-bold">#{{ $cart_id }}</div>
                                </div>
                                <div>{{ $cart->shippingAddress->fullname }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-10">
                <div class="d-flex flex-column gap-4 overflow-auto scrollbar bg-white shadow-sm" data-bs-spy="scroll" data-bs-target="#carts-nav" style="position: relative; height: calc(100vh - 3.5rem); z-index: 0" data-bs-offset="0" tabindex="0">
                    <form action="{{ route('vouchers.store') }}" method="post">
                        @csrf
                        
                        @foreach($carts as $cart_id => $cart)
                            <div id="cart-{{ $cart_id }}" class="p-4 border-bottom">
                                @includeWhen($cart->shippingMethod->name === 'ACS Courier', 'eshop::dashboard.voucher.partials.acs-form')
                                @includeWhen($cart->shippingMethod->name === 'SpeedEx', 'eshop::dashboard.voucher.partials.speedex-form')
                            </div>
                        @endforeach
                        
                        <button type="submit"></button>
                    </form>
                </div>
            </div>
        </div>

        @include('eshop::dashboard.voucher.modals.voucher-address-modal')
    </div>
@endsection
