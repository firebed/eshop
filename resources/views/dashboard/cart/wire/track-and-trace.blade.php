<div class="card shadow-sm">
    <div class="card-header">Διαχείριση κωδικών αποστολής</div>
    <div class="card-body">
        @if(filled($voucher))
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-500 text-primary lead">{{ $voucher }}</div>
                <x-bs::button.primary wire:click.prevent="trace()" data-bs-toggle="offcanvas" data-bs-target="#track-and-trace">Αναζήτηση</x-bs::button.primary>
            </div>
        @else
            <div class="d-flex">
                <x-bs::button.primary class="me-2">Έκδοση voucher</x-bs::button.primary>
                
                <x-bs::button.secondary wire:click.prevent="$toggle('showVoucherModal')" data-bs-toggle="modal" data-bs-target="#add-voucher-modal">Προσθήκη voucher</x-bs::button.secondary>
            </div>
        @endif

        {{--        <x-bs::dropdown>--}}
        {{--            <x-bs::dropdown.button id="voucher-dropdown" class="btn-secondary rounded-pill py-1 px-3 border-2 border-white btn-sm">--}}
        {{--                {{ $voucher ?? 'Voucher' }}--}}
        {{--            </x-bs::dropdown.button>--}}
        {{--            <x-bs::dropdown.menu button="voucher-dropdown">--}}
        {{--                <x-bs::dropdown.item wire:click="editVoucher"><em class="fa fa-edit text-secondary me-2"></em>{{ __('Edit tracking code') }}</x-bs::dropdown.item>--}}

        {{--                @if(filled($cart->voucher))--}}
        {{--                    @if($cart->shippingMethod)--}}
        {{--                        <x-bs::dropdown.item href="{{ $cart->shippingMethod?->getVoucherUrl($cart->voucher) }}" target="_blank">--}}
        {{--                            <em class="fas fa-external-link-alt text-secondary me-2"></em> Εμφάνιση--}}
        {{--                        </x-bs::dropdown.item>--}}

        {{--                        @if($cart->channel === 'skroutz')--}}
        {{--                            <x-bs::dropdown.item href="{{ route('carts.print-voucher', $cart) }}" target="_blank">--}}
        {{--                                <em class="fas fa-print text-secondary me-2"></em> Εκτύπωση δελτίου αποστολής--}}
        {{--                            </x-bs::dropdown.item>--}}
        {{--                        @endif--}}
        {{--                    @endif--}}
        {{--                @endif--}}
        {{--            </x-bs::dropdown.menu>--}}
        {{--        </x-bs::dropdown>--}}

        @include('eshop::dashboard.cart.partials.show.cart-voucher-modal')

        <div wire:ignore.self class="offcanvas offcanvas-end shadow px-0" tabindex="-1" id="track-and-trace" style="width: 500px">
            <div class="offcanvas-header border-bottom">
                <div class="fs-5 fw-500">Track & Trace</div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body scrollbar">
                <div wire:loading class="text-center w-100">
                    <div class="mb-3"><em class="fas fa-spinner fa-spin fa-3x text-gray-400"></em></div>
                    <div class="small text-secondary">Παρακαλώ περιμένετε όσο επικοινωνούμε<br>με τη μεταφορική εταιρεία.</div>
                </div>

                <div wire:loading.remove>
                    @foreach($checkpoints as $checkpoint)
                        <div wire:key="checkpoint-{{ $loop->index }}" class="d-flex mb-3 align-items-baseline">
                            <em class="fas fa-arrow-right me-2 text-green-500"></em>

                            <div>
                                <div class="fw-500">{{ $checkpoint['title'] }}</div>
                                <div class="small text-secondary">{{ $checkpoint['description'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>