<section id="trending-products" class="container-fluid py-4">
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
            <div @if(!$loop->first) x-cloak @endif x-show="active === '{{ $name }}'" class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100">
                            <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="card-body text-decoration-none text-dark">
                                <div class="vstack gap-1 h-100">
                                    <div class="ratio ratio-1x1">
                                        @if($src = $product->image?->url('sm'))
                                            <img src="{{ $src }}" alt="{{ $product->name }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                                        @endif
                                    </div>
                                    
                                    <small class="text-secondary">{{ $product->category->name }}</small>

                                    <div class="fs-6 fw-500 mb-3">{{ $product->name }}</div>
                                    <div class="mt-auto fw-bold">{{ format_currency($product->net_value) }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
