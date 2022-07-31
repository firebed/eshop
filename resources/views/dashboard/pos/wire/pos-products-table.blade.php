<div class="col-12 col-lg-5 col-xxl-4 bg-white d-none d-lg-flex flex-column shadow border-start" style="height: calc(100vh - 3.5rem)">
    <div class="d-grid">
        <div class="fw-500 fs-3">@isset($cart_id) Παραγγελία #{{ $cart_id }} @else Νέα παραγγελία @endisset</div>

        <div class="d-flex justify-content-between mb-3 small gap-3 text-secondary">
            @if ($submitted_at)
                <div>{{ $submitted_at->isoFormat('ll HH:mm') }}</div> @endif
            <div><em class="fas fa-weight-hanging me-2"></em>{{ format_weight($weight) }}</div>
        </div>

        <div class="row gx-3 mb-3">
            <div class="col-7">
                @livewire('dashboard.pos.products-search')
            </div>

            <div class="col">
                <div class="input-group">
                    <input wire:model.defer="barcode" wire:keydown.enter="searchBarcode" type="search" class="form-control" placeholder="Barcode" aria-label="Barcode">
                    <button wire:click.prevent="searchBarcode" class="btn btn-outline-secondary" type="button"><em class="fas fa-barcode"></em></button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 scrollbar overflow-auto">
        <table class="table table-striped small mb-0">
            <thead>
            <tr class="fw-500 table-light">
                <td class="w-5r"></td>
                <td>Προϊόν</td>
                <td class="text-center">Ποσότητα</td>
                <td class="text-end">Τιμή</td>
                <td class="text-center">Έκπτωση</td>
                <td class="text-end"></td>
            </tr>
            </thead>

            <tbody>
            @foreach($items as $id => $item)
                @php($product = $products->find($id))

                <tr wire:key="product-{{ $id }}">
                    <td>
                        <div class="ratio ratio-1x1 border rounded">
                            @if ($src = $product->image?->url('sm'))
                                <img src="{{ $src }}" alt="" class="img-top rounded">
                            @endif
                        </div>
                    </td>

                    <td>
                        <div class="vstack gap-1">
                            @if ($product->isVariant())
                                <div class="fw-500">{{ $product->parent->name }}</div>
                                <div class="text-secondary lh-sm">{{ $product->option_values }}</div>
                            @else
                                <div>{{ $product->name }}</div>
                            @endif
                            <div class="text-secondary lh-sm">{{ $product->sku }}</div>
                        </div>
                    </td>

                    <td class="text-center">
                        <input hidden wire:model.defer="items.{{ $id }}.quantity" type="text" name="items[{{ $id }}][quantity]"/>
                        {{ format_number($item['quantity']) }}
                    </td>

                    <td class="text-end">
                        <input hidden wire:model.defer="items.{{ $id }}.price" type="text" name="items[{{ $id }}][price]"/>
                        {{ format_currency($item['price']) }}
                    </td>

                    <td class="text-center">
                        <input hidden wire:model.defer="items.{{ $id }}.discount" type="text" name="items[{{ $id }}][discount]"/>
                        {{ format_percent($item['discount']) }}
                    </td>

                    <td>
                        <div class="vstack justify-content-end gap-2">
                            <button x-data x-on:click="
                                document.getElementById('product-id').value = {{ $id }}
                                document.getElementById('product-image').src = '{{ $src }}'
                                document.getElementById('product-trademark').innerText = '{{ $product->trademark }}'
                                document.getElementById('product-quantity').value = {{ $items[$id]['quantity'] }}
                                document.getElementById('product-price').value = {{ $items[$id]['price'] }}
                                document.getElementById('product-discount').value = {{ $items[$id]['discount'] }}
                                " type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#product-form">
                                <em class="fas fa-edit"></em>
                            </button>

                            <button type="button" wire:click.prevent="removeProduct({{ $id }})" class="btn btn-outline-secondary btn-sm">
                                <em class="fas fa-times"></em>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="v-stack gap-1 border-top p-2">
        <div class="d-flex justify-content-between fs-4 fw-bold">
            <div>Σύνολο</div>
            <div>{{ format_currency($total) }}</div>
        </div>
    </div>

    @include('eshop::dashboard.pos.partials.pos-product-form')
</div>