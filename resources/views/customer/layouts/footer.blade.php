<footer id="footer" class="container-fluid py-4">
    <div class="container-xxl">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-xl-4 g-4">
            <div class="col vstack align-items-start">
                <div class="fw-500 mb-2">{{ __("Security") }}</div>
                <a href="{{ route('pages.show', [app()->getLocale(), 'terms-of-service']) }}" class="text-dark text-hover-underline">{{ __("Terms of service") }}</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'data-protection']) }}" class="text-dark text-hover-underline">{{ __("Data protection") }}</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'return-policy']) }}" class="text-dark text-hover-underline">{{ __("Return policy") }}</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'cancellation-policy']) }}" class="text-dark text-hover-underline">{{ __("Cancellation policy") }}</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'secure-transactions']) }}" class="text-dark text-hover-underline">{{ __("Secure transactions") }}</a>
            </div>

            <div class="col vstack align-items-start">
                <div class="fw-500 mb-2">{{ __("Support") }}</div>
                <a href="{{ route('pages.show', [app()->getLocale(), 'shipping-methods']) }}" class="text-dark text-hover-underline">{{ __("Shipping methods") }}</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'payment-methods']) }}" class="text-dark text-hover-underline">{{ __("Payment methods") }}</a>
            </div>

            <div class="col vstack align-items-start">
                <div class="fw-500 mb-2">{{ __("Account") }}</div>
                <a href="{{ route('login', app()->getLocale()) }}" class="text-dark text-hover-underline">{{ __("Login") }}</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">{{ __("Register") }}</a>
                @auth
                    <a href="{{ route('account.orders.index', app()->getLocale()) }}" class="text-dark text-hover-underline">{{ __("My orders") }}</a>
                    <a href="{{ route('account.addresses.index', app()->getLocale()) }}" class="text-dark text-hover-underline">{{ __("My addresses") }}</a>
                @endauth
            </div>

            <div class="col vstack align-items-start">
                <div class="fw-500 mb-2">{{ __("Contact") }}</div>
                <div>{{ __("company.address") }}</div>
                <div>{!! implode('<br>', __("company.phone")) !!}</div>
                <div>{{ __("company.email") }}</div>
            </div>
        </div>

        <hr>

        @if(is_array(eshop("social")) && eshop('social') > 0)
            <div class="d-flex gap-2 justify-content-end">
                @if(eshop("social.facebook"))
                    <a href="{{ eshop("social.facebook") }}"><em class="fab fa-facebook-square fa-2x"></em></a>
                @endif

                @if(eshop("social.instagram"))
                    <a href="{{ eshop("social.instagram") }}"><em class="fab fa-instagram-square fa-2x"></em></a>
                @endif
            </div>
        @endif
    </div>
</footer>

<div class="container-fluid bg-white border-top py-3">
    <div class="container-xxl">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-secondary">&copy; {{ now()->year }} {{ config('app.name') }}</small>
            <div><img src="{{ asset('images/credit-cards.webp') }}" alt="" class="img-fluid"></div>
        </div>
    </div>
</div>
