<div class="vstack h-100">
    <div class="vstack mb-3">
        <div class="fw-500 fs-3">@isset($cart->id) Παραγγελία #{{ $cart->id }} @else Νέα παραγγελία @endisset</div>
        <div class="text-secondary small d-flex justify-content-between">
            <span>@if ($submitted_at) {{ $submitted_at->isoFormat('dddd, LL HH:mm') }} @else &nbsp; @endif</span>
            <span><em class="fas fa-weight-hanging me-2"></em>{{ format_weight($weight) }}</span>
        </div>
    </div>

    <div class="row gx-3 mb-3">
        <div class="col-8">
            @livewire('dashboard.pos.products-search')
        </div>

        <div class="col">
            <div class="input-group">
                <input wire:model.defer="barcode" wire:keydown.enter="searchBarcode" type="text" class="form-control" placeholder="Barcode" aria-label="Barcode">
                <button wire:click.prevent="searchBarcode" class="btn btn-outline-secondary" type="button"><em class="fas fa-barcode"></em></button>
            </div>
        </div>
    </div>

    <div class="table-responsive" style="height: calc(100vh - 21.45rem)">
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
                        <div class="hstack justify-content-end gap-2">
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

    <hr>

    <div class="v-stack gap-1">
        <div class="d-flex justify-content-between fs-4 fw-bold mb-2">
            <div>Σύνολο</div>
            <div>{{ format_currency($total) }}</div>
        </div>

        <div class="d-flex justify-content-between text-secondary">
            <div>Μεταφορικά</div>
            <div>{{ format_currency($shipping_fee) }}</div>
        </div>

        <div class="d-flex justify-content-between text-secondary">
            <div>Πληρωμή</div>
            <div>{{ format_currency($payment_fee) }}</div>
        </div>
    </div>

    @include('eshop::dashboard.pos.partials.pos-product-form')
</div>