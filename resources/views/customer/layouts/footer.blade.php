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
    </div>
</footer>
