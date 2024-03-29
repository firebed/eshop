<div class="hstack gap-2 align-items-center flex-wrap">
    <div class="bg-blue-400 rounded-pill py-1 px-3 border-2 border-white text-nowrap btn-sm">
        {{ $cart->channel }}
    </div>

    <a href="#" class="btn btn-warning rounded-pill py-1 px-3 border-2 border-white text-nowrap btn-sm" wire:click.prevent="exportToPdf">
        <em class="fa fa-print"></em> {{ __("Print") }}
    </a>

    @if($cart->submitted_at)
        <x-bs::dropdown>
            @if($status)
                <x-bs::dropdown.button wire:loading.attr="disabled" wire:target="resetStatus, editCartStatus" id="statuses-dropdown" class="btn-{{ $status->color }} rounded-pill py-1 px-3 btn-sm border-2 border-white">
                    {{ __("eshop::cart.status.action.$status->name") }}
                </x-bs::dropdown.button>
            @endif

            <x-bs::dropdown.menu button="statuses-dropdown">
                @foreach($statuses as $stats)
                    @foreach($stats as $stat)
                        <x-bs::dropdown.item wire:click.prevent="editCartStatus({{ $stat->id }})" wire:key="stat-{{ $stat->id }}">
                            {{ __("eshop::cart.status.action.$stat->name") }}
                        </x-bs::dropdown.item>
                    @endforeach
                    <x-bs::dropdown.divider/>
                @endforeach
                <x-bs::dropdown.item wire:click.prevent="resetStatus">{{ __("Reset status") }}</x-bs::dropdown.item>
            </x-bs::dropdown.menu>
        </x-bs::dropdown>

        @include('eshop::dashboard.cart.partials.show.cart-status-modal')
    @endif

    @can('Manage invoices')
        @if($cart->isDocumentInvoice())
            <a href="{{ route('carts.invoice', $cart) }}" target="_blank" class="btn btn-alt rounded-pill py-1 px-3 border-2 border-white text-nowrap btn-sm">
                <em class="fa fa-file"></em> Τιμολόγιο
            </a>
        @endif
    @endcan

    @can('Manage orders')
        @can('Delete orders')
            <x-bs::dropdown>
                <button type="button" class="btn btn-haze btn-sm py-1 px-3 rounded-pill border-2 border-white text-nowrap" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('More') }} <em class="fas small text-secondary fa-chevron-down"></em>
                </button>
                <x-bs::dropdown.menu button="more">
                    <x-bs::dropdown.item wire:click.prevent="confirmDelete"><em class="far fa-trash-alt text-secondary me-2"></em>{{ __('Delete') }}</x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <form wire:submit.prevent="deleteCart">
                <x-bs::modal wire:model.defer="showConfirmDelete">
                    <x-bs::modal.body>
                        <div class="d-grid gap-3 text-center">
                            <div><em class="far fa-trash-alt fa-5x text-red-400"></em></div>
                            <div class="fs-4 text-secondary">{{ __("Are you sure?") }}</div>
                            <div class="text-secondary">{{ __("Are you sure you want to delete the this cart? This action cannot be undone.") }}</div>
                            <div>
                                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                                <x-bs::button.danger type="submit" class="ms-2 px-3">{{ __("Delete") }}</x-bs::button.danger>
                            </div>
                        </div>
                    </x-bs::modal.body>
                </x-bs::modal>
            </form>
        @endcan
    @endcan
</div>
