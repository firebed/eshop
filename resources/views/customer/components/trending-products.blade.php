<section class="container-fluid py-3 bg-white">
    <div class="container-xxl" x-data="{ active: '{{ $categories->keys()->first() }}' }">
        <div class="fw-bold py-5 vstack gap-3">
            <h2 class="mx-auto">Trending</h2>
            <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>

            <div class="d-flex flex-wrap gap-3 mx-auto">
                @foreach($categories as $name => $products)
                    <button x-on:click.prevent="active = '{{ $name }}'" class="btn rounded-pill px-3 btn-sm" x-bind:class="{'btn-primary': active === '{{ $name }}'}">{{ $name }}</button>
                @endforeach
            </div>
        </div>

        @foreach ($categories as $name => $products)
            <div x-show="active === '{{ $name }}'" class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="vstack gap-2 h-100">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                                @if($src = $product->image?->url('sm'))
                                    <img src="{{ $src }}" alt="{{ $product->name }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                                @endif
                            </a>

                            <div class="fs-6 fw-500"><a href="{{ productRoute($product) }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></div>
                            <div class="mt-sm-auto fw-bold">{{ format_currency($product->net_value) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
