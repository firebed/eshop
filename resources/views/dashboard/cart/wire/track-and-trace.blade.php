<div class="card shadow-sm">
    <div class="card-body fw-500">Διαχείριση κωδικών αποστολής</div>

    <ul class="nav nav-tabs px-3">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#active-vouchers">Ενεργά ({{ $vouchers->filter->isActive->count() }})</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#cancelled-vouchers">Ακυρωμένα ({{ $vouchers->filter->isDeleted->count() }})</a>
        </li>
    </ul>

    <div class="tab-content pt-3">
        <div class="tab-pane show active" id="active-vouchers">
            @foreach($vouchers->filter->isActive as $voucher)
                <div wire:key="voucher-{{ $voucher->id }}" class="px-3 pb-1 d-flex align-items-center justify-content-between">
                    <div class="text-indigo-600 fw-bold">{{ $voucher->number }}</div>

                    <div class="d-flex gap-1">
                        <x-bs::button.white wire:click.prevent="trace({{ $voucher->id }})" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important" class="text-indigo-600">
                            <span wire:loading wire:target="trace({{ $voucher->id }})"><em class="fas fa-spinner fa-spin"></em></span>
                            <span wire:loading.remove wire:target="trace({{ $voucher->id }})"><em class="fas fa-search"></em></span>
                        </x-bs::button.white>

                        <x-bs::button.white wire:click="printVoucher({{ $voucher->id }})" wire:loading.attr="disabled" wire:target="printVoucher({{ $voucher->id }})" size="sm" style="width: 2.5rem !important" class="text-teal-600">
                            <span wire:loading wire:target="printVoucher({{ $voucher->id }})"><em class="fas fa-spinner fa-spin"></em></span>
                            <em wire:loading.remove wire:target="printVoucher({{ $voucher->id }})" class="fas fa-print"></em>
                        </x-bs::button.white>

                        <x-bs::dropdown>
                            <x-bs::dropdown.button id="more-{{ $voucher->id }}" class="btn-white text-gray-600">
                            </x-bs::dropdown.button>

                            <x-bs::dropdown.menu button="more-{{ $voucher->id }}" class="shadow">
                                @if($voucher->is_manual)
                                    <x-bs::dropdown.item wire:click.prevent="editVoucher({{ $voucher->id }})">Επεξεργασία</x-bs::dropdown.item>
                                    <x-bs::dropdown.divider/>
                                @endif

                                <x-bs::dropdown.item wire:click.prevent="cancelVoucher({{ $voucher->id }})">Ακύρωση κωδικού</x-bs::dropdown.item>

                                @if($voucher->is_manual)
                                    <x-bs::dropdown.item wire:click.prevent="deleteVoucher({{ $voucher->id }})">Διαγραφή κωδικού</x-bs::dropdown.item>
                                @endif
                            </x-bs::dropdown.menu>
                        </x-bs::dropdown>
                    </div>
                </div>
            @endforeach

            <div class="d-flex flex-column flex-sm-row border-top gap-2 p-3 mt-2">
                <div class="col-12 col-sm d-grid">
                    <x-bs::button.white wire:click.prevent="showBuyVoucherModal()"><em class="fas fa-star-of-life text-gray-600"></em> Έκδοση νέου</x-bs::button.white>
                </div>

                <div class="col-12 col-sm d-grid">
                    <x-bs::button.white wire:click.prevent="createVoucher()" data-bs-toggle="modal" data-bs-target="#add-voucher-modal" class="col">
                        <em class="fas fa-plus text-gray-600"></em>
                        Προσθήκη
                    </x-bs::button.white>
                </div>
            </div>
        </div>

        <div class="tab-pane pb-3" id="cancelled-vouchers">
            <x-bs::table size="sm" class="small" hover>
                @foreach($vouchers->filter->isDeleted as $voucher)
                    <tr wire:key="voucher-{{ $voucher->id }}">
                        <td class="text-secondary ps-3 align-middle">{{ $voucher->number }}</td>

                        <td class="text-end pe-3 align-middle">
                            {{--                            <x-bs::button.white wire:click.prevent="trace()" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important" class="text-indigo-600">--}}
                            {{--                                <span wire:loading wire:target="trace"><em class="fas fa-spinner fa-spin"></em></span>--}}
                            {{--                                <span wire:loading.remove wire:target="trace"><em class="fas fa-search"></em></span>--}}
                            {{--                            </x-bs::button.white>--}}

                            {{--                            <x-bs::button.white wire:click="print('{{ $voucher }}')" wire:loading.attr="disabled" size="sm" style="width: 2.5rem !important" class="text-teal-600">--}}
                            {{--                                <span wire:loading wire:target="print"><em class="fas fa-spinner fa-spin"></em></span>--}}
                            {{--                                <em wire:loading.remove wire:target="print" class="fas fa-print"></em>--}}
                            {{--                            </x-bs::button.white>--}}

                            <x-bs::dropdown>
                                <button type="button" class="btn btn-white btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <em class="fas fa-bars"></em>
                                </button>

                                <x-bs::dropdown.menu button="more-{{ $voucher->id }}" class="shadow">
                                    @if($voucher->is_manual)
                                        <x-bs::dropdown.item>Επεξεργασία</x-bs::dropdown.item>
                                    @endif
                                </x-bs::dropdown.menu>
                            </x-bs::dropdown>
                        </td>
                    </tr>
                @endforeach
            </x-bs::table>
        </div>
    </div>

    @include('eshop::dashboard.cart.partials.show.cart-voucher-modal')

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

    <form wire:submit.prevent="purchaseVoucher()">
        <x-bs::modal wire:model.defer="showBuyVoucherModal">
            <x-bs::modal.header>Αγορά Voucher</x-bs::modal.header>

            <x-bs::modal.body>
                <div class="row row-cols-2">
                    <x-bs::input.group label="Μεταφορική" for="couriers" class="col">
                        <x-bs::input.select wire:model.defer="courier_id" id="couriers">
                            @foreach($couriers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.group>

                    <x-bs::input.group label="Αριθμός τεμαχίων" for="items" class="col">
                        <x-bs::input.integer wire:model.defer="itemsCount" id="items"/>
                    </x-bs::input.group>
                </div>
            </x-bs::modal.body>

            <x-bs::modal.footer>
                <x-bs::modal.close-button>Άκυρο</x-bs::modal.close-button>
                <x-bs::button.primary type="submit">Αγορά</x-bs::button.primary>
            </x-bs::modal.footer>
        </x-bs::modal>
    </form>
</div>