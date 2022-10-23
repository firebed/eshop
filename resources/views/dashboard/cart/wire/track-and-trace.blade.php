<div class="card shadow-sm">
    <div class="card-body fw-500">Διαχείριση κωδικών αποστολής</div>
    @if(filled($voucher))
        <div class="px-3 pb-3 d-flex align-items-center justify-content-between">
            <div class="fw-500 text-dark">{{ $voucher }}</div>

            <div class="d-flex gap-1">
                <x-bs::button.white wire:click.prevent="trace()" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important">
                    <span wire:loading wire:target="trace"><em class="fas fa-spinner fa-spin"></em></span>
                    <span wire:loading.remove wire:target="trace"><em class="fas fa-search"></em></span>
                </x-bs::button.white>

                <x-bs::button.white wire:click="print('{{ $voucher }}')" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important">
                    <span wire:loading wire:target="print"><em class="fas fa-spinner fa-spin"></em></span>
                    <em wire:loading.remove wire:target="print" class="fas fa-print"></em>
                </x-bs::button.white>

                <x-bs::button.white wire:click="delete" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important">
                    <span wire:loading wire:target="delete"><em class="fas fa-spinner fa-spin"></em></span>
                    <em wire:loading.remove wire:target="delete" class="fas fa-trash"></em>
                </x-bs::button.white>
            </div>
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row border-top gap-2 p-3">
        <div class="col-12 col-sm d-grid">
            <x-bs::button.white><em class="fas fa-star-of-life text-gray-600"></em> Έκδοση νέου</x-bs::button.white>
        </div>

        <div class="col-12 col-sm d-grid">
            <x-bs::button.white wire:click.prevent="$toggle('showVoucherModal')" data-bs-toggle="modal" data-bs-target="#add-voucher-modal" class="col">
                <em class="fas fa-plus text-gray-600"></em>
                Προσθήκη
            </x-bs::button.white>
        </div>
    </div>

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

    {{--    @include('eshop::dashboard.cart.partials.show.cart-voucher-modal')--}}

    <div wire:ignore.self
         x-data="{ show: @entangle('show'), offcanvas: null }"
         x-init="
            offcanvas = new bootstrap.Offcanvas($el)
            $watch('show', () => { if(show) offcanvas.show() })
            $el.addEventListener('hide.bs.offcanvas', () => show = false)            
         "
         x-on:keydown.escape="show = false"
         class="offcanvas offcanvas-end shadow px-0" tabindex="-1" id="track-and-trace" style="width: 500px">
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