<div class="vstack gap-3">
    <div class="d-flex justify-content-between gap-3 flex-wrap">
        <div class="col-12 col-sm-8 position-relative" x-data="{ show: false }">
            <input wire:model="search"
                   x-on:focus="show = true"
                   x-on:keydown.escape="show = false"
                   x-on:click.outside="show = false"
                   type="search" 
                   class="form-control" 
                   placeholder="{{ __("Search products") }}">
            
            @if($search_results->isNotEmpty())
                <div x-show="show" wire:key="search-results" class="rounded border shadow position-absolute bg-white vstack overflow-auto scrollbar w-100" style="height: 350px; z-index: 5000">
                    @foreach($search_results as $result)
                        <a wire:key="result-{{ $result->id }}" wire:click.prevent="addProduct({{ $result->id }})" href="#" class="d-flex gap-3 border-bottom p-2 list-group-item-action text-decoration-none">
                            <div class="ratio ratio-4x3 rounded w-4r">
                                <img src="{{ $result->image->url('sm') }}" alt="" class="img-top">
                            </div>
                            <div class="vstack">
                                <div>{{ $result->trademark }}</div>
                                <div>{{ format_currency($result->price) }}</div>
                                <div>{{ format_number($result->stock) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="ms-auto">
            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#label-print-dialog" type="button">
                <em class="fas fa-print"></em> {{ __("Print") }}
            </button>
        </div>
    </div>
    
    <div class="table-responsive bg-white border rounded shadow-sm">
        <table class="table table-hover mb-0">
            <thead>
            <tr class="fw-500 table-light">
                <td class="w-5r"></td>
                <td>Περιγραφή</td>
                <td class="text-end">Τιμή</td>
                <td class="w-6r text-center">Ποσότητα</td>
                <td class="text-end"></td>
            </tr>
            </thead>

            <tbody x-data>
            @foreach($products as $product)
                <tr wire:key="product-{{ $product->id }}">
                    <td>
                        <div class="ratio ratio-1x1 rounded border">
                            <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
                        </div>
                    </td>
                    <td class="align-baseline">{{ $product->trademark }}</td>
                    <td class="text-end align-baseline">{{ format_currency($product->price) }}</td>
                    <td class="text-end align-baseline">
                        <input type="hidden" name="labels[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                        
                        <input x-on:keydown="if($event.key === '.') $event.preventDefault()"
                               wire:model.defer="labels.{{ $product->id }}"
                               type="number"
                               class="form-control input-spin-none text-center"
                               name="labels[{{ $loop->index }}][quantity]"
                               min="1"
                               step="1">
                    </td>
                    <td class="text-end align-baseline">
                        <a href="#" wire:click.prevent="removeProduct({{ $product->id }})" wire:loading.class="disabled" class="btn shadow-none">
                            <em class="far fa-trash-alt"></em>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <caption>
                <x-eshop::wire-pagination :paginator="$products"/>
            </caption>
        </table>
    </div>
</div>