<x-bs::card>
    <x-bs::card.body class="d-grid gap-2">
        <div class="d-flex gap-1 justify-content-end align-items-center">

            @livewire('dashboard.cart.cart-item-create-modal', ['cartId' => $cart->id])

            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-haze btn-sm" id="cart-actions">{{ __("Actions") }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="cart-actions" alignment="right">
                    <x-bs::dropdown.item wire:click.prevent="openDiscountModal">
                        <em class="fa fa-percentage me-2 text-gray-600"></em> {{ __("Set discount") }}
                    </x-bs::dropdown.item>

                    <x-bs::dropdown.divider/>

                    <x-bs::dropdown.item wire:click.prevent="confirmDelete">
                        <em class="far fa-trash-alt me-2 text-gray-600"></em> {{ __("Delete") }}
                    </x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </div>

        <div class="table-responsive">
            <x-bs::table>
                <thead>
                <tr>
                    <td class="w-1r">
                        <x-bs::input.checkbox wire:model="selectAll" id="checkAll"/>
                    </td>
                    <td class="w-5r">&nbsp;</td>
                    <td>{{ __("Description") }}</td>
                    <td class="text-end">{{ __("Quantity") }}</td>
                    <td class="text-end">{{ __("Price") }}</td>
                    <td class="text-end">{{ __("Discount") }}</td>
                    <td class="text-end">{{ __("Total") }}</td>
                    <td class="text-end">&nbsp;</td>
                </tr>
                </thead>

                <tbody>
                @foreach($products as $product)
                    <tr wire:key="row-{{ $product->pivot->id }}">
                        <td>
                            <x-bs::input.checkbox wire:model="selected" id="check-{{ $product->id }}" value="{{ $product->pivot->id }}"/>
                        </td>
                        <td>
                            <div class="ratio ratio-1x1 w-5r">
                                @if($product->image && $src = $product->image->url('sm'))
                                    <img class="img-top rounded" src="{{ $src }}" alt="{{ $product->name }}">
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="vstack gap-1">
                                @if($product->isVariant())
                                    <a href="{{ route('variants.edit', $product->id) }}" class="text-hover-underline">
                                        <span class="fw-500">{{ $product->options->pluck('pivot.value')->join(' - ') }}</span>
                                        <small class="text-secondary">(SKU: {{ $product->sku }})</small>
                                    </a>
                                    <div class="text-secondary">{{ $product->parent->name }}</div>
                                @else
                                    <div class="fw-500"><span class="text-secondary">{{ $product->sku }}</span> <span>{{ $product->name }}</span></div>
                                @endif
                                <div class="hstack gap-2 align-items-baseline text-nowrap">
                                    @if($product->stock > $product->available_gt)
                                        <span class="fw-500 rounded-pill small bg-teal-200 px-3"><em class="fas fa-boxes text-secondary me-2"></em>{{ format_number($product->stock) }}</span>
                                    @elseif($product->stock == $product->available_gt)
                                        <span class="fw-500 rounded-pill small bg-yellow-200 px-3"><em class="fas fa-boxes text-secondary me-2"></em>{{ format_number($product->stock) }}</span>
                                    @else
                                        <span class="fw-500 rounded-pill small bg-red-400 px-3"><em class="fas fa-boxes me-2"></em>{{ format_number($product->stock) }}</span>
                                    @endif
                                    <span class="fw-500 rounded-pill small bg-gray-200 px-3"><em class="fas fa-weight-hanging text-secondary me-2"></em>{{ format_weight($product->weight) }}</span>
{{--                                    <small class="text-secondary">{{ $product->pivot->created_at->format('d/m/y H:i:s') }}</small>--}}
                                </div>
                            </div>
                        </td>
                        <td class="text-end">{{ format_number($product->pivot->quantity) }}</td>
                        <td class="text-end">{{ format_currency($product->pivot->price) }}</td>
                        <td class="text-end">{{ format_percent($product->pivot->discount) }}</td>
                        <td class="text-end">{{ format_currency($product->pivot->total) }}</td>
                        <td class="text-end">
                            <x-bs::button.white wire:loading.attr="disabled" wire:click="edit({{ $product->pivot->id }})" wire:target="edit({{ $product->pivot->id }})" size="sm">
                                <em class="far fa-edit"></em>
                            </x-bs::button.white>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-bs::table>
        </div>
    </x-bs::card.body>
</x-bs::card>
