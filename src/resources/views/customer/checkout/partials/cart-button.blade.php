<a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="position-relative">
    <em class="fa fa-shopping-basket fa-2x text-secondary"></em>
    <div class="bg-primary position-absolute top-0 end-0 translate-middle-y rounded-circle" style="width: 22px; height: 22px">
        <small id="cart-checkout" class="text-light position-absolute top-50 start-50 translate-middle lh-sm" style="font-size: .7rem">{{ $count }}</small>
    </div>
</a>
