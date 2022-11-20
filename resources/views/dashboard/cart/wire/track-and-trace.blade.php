<div class="position-relative card shadow-sm">
    {{--    <div class="card-body fw-500">Διαχείριση κωδικών αποστολής</div>--}}

    <div class="card-body">
        @if($currentVoucher)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-500">{{ $currentVoucher->number }}</div>
                <div><img src="{{ $icons[$currentVoucher->courier] }}" class="img-fluid" style="max-height: 24px; max-width: 80px" alt=""></div>
            </div>
        @endif

        <div class="btn-group w-100">
            @if($currentVoucher)
                <button type="button" wire:click="printVoucher({{ $currentVoucher->id }})" wire:loading.attr="disabled" class="col-8 btn btn-primary">
                    <span wire:loading wire:target="printVoucher"><em class="fa fa-spinner fa-spin"></em></span>
                    <span wire:loading.remove wire:target="printVoucher"><em class="fas fa-print fa-sm"></em> Εκτύπωση voucher</span>
                </button>

                <button type="button" wire:click="trace({{ $currentVoucher->id }})" wire:loading.attr="disabled" class="col-2 btn btn-outline-primary">
                    <em wire:loading wire:target="trace" class="fa fa-spinner fa-spin"></em>
                    <em wire:loading.remove wire:target="trace" class="fas fa-map-marked-alt"></em>
                </button>
            @else
                <button type="button" wire:click="showBuyVoucherModal()" class="col-8 btn btn-primary"><em class="fas fa-plus fa-sm"></em> Έκδοση voucher</button>
            @endif

            <button type="button" class="col-2 btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                @if($currentVoucher === null)
                    <li><a class="dropdown-item" href="#" wire:click.prevent="createVoucher()"><em class="fas fa-pencil-alt text-secondary me-2"></em> Χειροκίνητη εισαγωγή</a></li>
                @else
                    @if($currentVoucher->is_manual)
                        <li><a class="dropdown-item" href="#" wire:click.prevent="editVoucher({{ $currentVoucher->id }})"><em class="fas fa-pencil-alt text-secondary me-2"></em> Επεξεργασία</a></li>
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    <li><a class="dropdown-item" href="#" wire:click.prevent="$toggle('showDeleteVoucherModal')"><em class="fas fa-trash-alt text-secondary me-2"></em> Διαγραφή voucher</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div wire:loading class="position-absolute start-0 end-0 w-100 h-100 opacity-50 bg-gray-100"></div>

    @include('eshop::dashboard.cart.partials.show.cart-voucher-modal')
    @include('eshop::dashboard.cart.partials.show.cart-buy-voucher-modal')
    @includeWhen($currentVoucher, 'eshop::dashboard.cart.partials.show.delete-voucher-modal')

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