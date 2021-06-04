<div>
    <div class="fs-5 mb-3 pb-2 border-bottom">{{ __("Feature Products") }}</div>

    <x-bs::slider slides="1" slides-md="2" slides-lg="3" slides-xl="4" interval="4000" class="rounded-3 gx-2">
        @foreach($products as $product)
            <x-bs::slider.item class="w-md-50 w-lg-1/3 w-xl-25 pb-1">
                <x-bs::card class="h-100 shadow-none">
                    <div class="ratio ratio-1x1">
                        <img class="img-middle rounded-top" src="{{ $product->image->url('sm') }}" alt="">
                    </div>
                    <x-bs::card.body>
                        <div class="small mb-2 text-secondary">{{ $product->parent->name }}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6><a href="#" class="text-decoration-none">{{ $product->name }} {{ $product->sku }}</a></h6>
                                <h6 class="fw-light">{{ format_currency($product->price) }}</h6>
                            </div>
                            <a type="button" class="bg-light rounded-circle border d-flex justify-content-center align-items-center text-decoration-none" style="width: 38px; height: 38px">
                                <em class="fa fa-shopping-basket text-gray-600"></em>
                            </a>
                        </div>
                    </x-bs::card.body>
                </x-bs::card>
            </x-bs::slider.item>
        @endforeach

        <x-bs::slider.nav/>
    </x-bs::slider>
</div>
