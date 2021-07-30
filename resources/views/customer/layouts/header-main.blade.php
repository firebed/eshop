<div class="row align-items-center">
    <a href="{{ route('home', app()->getLocale()) }}" class="col-3">
        <img class="img-fluid" src="{{ asset(config('eshop.logo')) }}" alt="{{ config('app.name') }}" height="{{ config('eshop.logo_height') }}" width="{{ config('eshop.logo_width') }}">
    </a>

    <div class="col">
        <label class="d-none" for="search-bar">{{ __("Search") }}</label>
        <input id="search-bar" type="text" class="form-control rounded-pill" placeholder="{{ __("I'm looking for...")}}">
    </div>

    <div class="col-3 justify-content-center d-none d-md-flex">
        <em class="fa fa-mobile-alt fa-2x text-primary me-2"></em>
        <div class="small lh-sm fw-500">{!! collect(__("company.phone"))->join('<br>') !!}</div>
    </div>

    <div class="col-auto d-flex justify-content-end">
        <livewire:customer.checkout.cart-button/>
    </div>
</div>
