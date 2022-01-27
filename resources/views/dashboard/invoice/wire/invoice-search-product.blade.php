<x-bs::modal wire:model.defer="showModal" size="lg">
    <x-bs::modal.header>
        <input type="text" wire:model="search" class="form-control">
    </x-bs::modal.header>

    <x-bs::modal.body>
        <table class="table table-sm m-0">
            <thead>
            <tr>
                <td class="fw-500"></td>
                <td class="fw-500">Περιγραφή</td>
                <td class="fw-500 text-end">Απόθεμα</td>
                <td class="fw-500 text-end">Τιμή</td>
                <td class="fw-500 text-end">Έκπτωση</td>
            </tr>
            </thead>
            
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td class="w-3r">
                        @if($product->image && ($src = $product->image->url('sm')))
                            <div class="ratio ratio-1x1">
                                <img src="{{ $src }}" alt="" class="rounded img-top">
                            </div>
                        @endif
                    </td>
                    
                    <td class="align-middle">
                        <a href="#" class="text-decoration-none" wire:click.prevent="addProduct({{ $product->id }})">{{ $product->trademark }}</a>
                    </td>
                    
                    <td class="align-middle text-end">{{ $product->stock }}</td>
                    
                    <td class="align-middle text-end">{{ format_currency($product->price) }}</td>
                    
                    <td class="align-middle text-end">{{ format_percent($product->discount) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-bs::modal.body>
</x-bs::modal>