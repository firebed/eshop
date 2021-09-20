<div class="table-responsive">
    <x-bs::table>
        <thead>
        <tr>
            <td class="w-4r">{{ __('eshop::product.image') }}</td>
            <td>{{ __('eshop::product.name') }}</td>
            <td></td>
        </tr>
        </thead>

        <tbody>
        @foreach($products as $product)
            <tr>
                <td>
                    <div class="ratio ratio-1x1 rounded border">
                        @if($product->image && $src = $product->image->url('sm'))
                            <img src="{{ $src }}" alt="{{ $product->name }}" class="img-middle rounded">
                        @endif
                    </div>
                </td>
                <td class="align-middle">
                    <div class="d-grid">
                        <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">{{ $product->name }}</a>
                        <small class="text-secondary">{{ $product->category->name }}</small>
                    </div>
                </td>
                <td class="text-end align-middle">
                    <form action="{{ route('collections.detachProduct', [$collection, $product]) }}" method="post"
                        x-data="{ submitting: false }"
                        x-on:submit="submitting = true"
                    >
                        @csrf
                        @method('delete')
                        <button x-bind:disabled="submitting" type="submit" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$products"/>
        </caption>
    </x-bs::table>
</div>