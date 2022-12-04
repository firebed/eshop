<div class="position-relative card shadow-sm">
    {{--    <div class="card-body fw-500">Διαχείριση κωδικών αποστολής</div>--}}

    <div class="card-body">
        @if($currentVoucher)
            @if($cart->channel === 'skroutz')
                <div class="mb-3"><span class="badge rounded-pill bg-orange-500 text-gray-100">Skroutz</span></div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-500">{{ $currentVoucher->number }}</div>
                <div><img src="{{ $icons[$currentVoucher->courier_id] }}" class="img-fluid" style="max-height: 24px; max-width: 80px" alt=""></div>
            </div>
        @endif

        <div class="btn-group w-100">
            @if($currentVoucher)
                @if(filled($cart->reference_id) && $cart->channel === 'skroutz')
                    <a href="{{ route('carts.print-voucher', $cart) }}" target="_blank" type="button" class="col-8 btn btn-primary">
                        <em class="fas fa-print fa-sm"></em> Εκτύπωση voucher
                    </a>
                @else
                    <button type="button" wire:click="printVoucher({{ $currentVoucher->id }})" wire:loading.attr="disabled" class="col-8 btn btn-primary">
                        <em wire:loading wire:target="printVoucher" class="fa fa-spinner fa-spin"></em>
                        <em wire:loading.remove wire:target="printVoucher" class="fas fa-print fa-sm"></em>
                        Εκτύπωση voucher
                    </button>

                    <button type="button" wire:click="trace({{ $currentVoucher->id }})" wire:loading.attr="disabled" class="col-2 btn btn-outline-primary">
                        <em wire:loading wire:target="trace" class="fa fa-spinner fa-spin"></em>
                        <em wire:loading.remove wire:target="trace" class="fas fa-map-marked-alt"></em>
                    </button>
                @endif
            @else
                <button x-data="{ disabled: false }"
                        @click.prevent="disabled = true; $wire.emitTo('dashboard.voucher.create', 'createVoucher', {{ $cart->id }}, 1)"
                        x-on:create-voucher-shown.window="disabled = false"
                        :disabled="disabled"
                        type="button" class="col-8 btn btn-primary"><em class="fas fa-plus fa-sm"></em> Έκδοση voucher</button>
            @endif

            @if($cart->channel !== 'skroutz')
                <button type="button" class="col-2 btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    @if($currentVoucher === null)
                        <li><a class="dropdown-item" href="#" wire:click.prevent="createVoucher()"><em class="fas fa-pencil-alt text-secondary me-2"></em> Χειροκίνητη εισαγωγή</a></li>
                    @else
                        @if($currentVoucher->is_manual)
                            <li><a class="dropdown-item" href="#" wire:click.prevent="editVoucher({{ $currentVoucher->id }})"><em class="fas fa-pencil-alt text-secondary me-2"></em> Επεξεργασία</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif
                        <li><a class="dropdown-item" href="#" wire:click.prevent="$toggle('showDeleteVoucherModal')"><em class="fas fa-trash-alt text-secondary me-2"></em> Διαγραφή voucher</a></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>

    <div wire:loading class="position-absolute start-0 end-0 w-100 h-100 opacity-50 bg-gray-100"></div>

    @include('eshop::dashboard.cart.partials.show.cart-voucher-modal')
    <livewire:dashboard.voucher.create/>
    @includeWhen($currentVoucher, 'eshop::dashboard.cart.partials.show.delete-voucher-modal')

    <div wire:ignore.self
         x-data="{ show: @entangle('showTrace'), offcanvas: null }"
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