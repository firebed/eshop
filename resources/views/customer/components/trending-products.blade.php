<section class="container-fluid py-4">
    <div class="container-xxl">
        <h2 class="fw-normal fs-4 mb-2r"><a href="{{ route('products.collections.index', [app()->getLocale(), 'trending']) }}" class="text-dark text-decoration-none">{{ __("Trending") }}</a></h2>

        <div class="slider-container">
            <x-eshop::slider autoplay="false" class="gx-4">
                @foreach($products as $product)
                    <x-eshop::slider.item class="w-100 w-sm-50 w-md-1/3 w-lg-25 w-xl-20 w-xxl-1/6">
                        <x-eshop::product-preview :product="$product"/>
                    </x-eshop::slider.item>
                @endforeach
            </x-eshop::slider>

            <x-eshop::slider.nav/>
        </div>
    </div>
</section>
