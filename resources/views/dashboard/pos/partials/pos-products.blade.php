@foreach($products as $product)
    <div class="col">
        <x-bs::card class="h-100">
            <a x-on:click.prevent="@if($product->has_variants) loadVariants({{ $product->id }}) @endif" href="#" class="d-grid gap-2 p-2 text-decoration-none text-dark list-group-item-action h-100 rounded">
                <div class="ratio ratio-16x9">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $product->name }}" class="img-middle rounded">
                    @else
                        <em class="fas fa-image fa-4x img-middle text-gray-500"></em>
                    @endif
                </div>

                <small class="text-center">{{ $product->name }}</small>
            </a>
        </x-bs::card>
    </div>
@endforeach
