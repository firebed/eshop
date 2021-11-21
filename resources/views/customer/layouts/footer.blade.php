<footer id="footer" class="container-fluid">
    <div class="container-xxl">
        <div class="row gy-4 gx-5">
            <div class="col-12 col-lg-3">
                <div class="vstack align-items-start gap-3">
                    <div id="footer-logo"></div>
                    <div class="text-secondary">{{ __("company.seo.title") }}</div>

                    @if(is_array(eshop("social")) && eshop('social') > 0)
                        <div class="hstack gap-2">

                            @if(eshop("social.facebook"))
                                <a href="{{ eshop("social.facebook") }}"><em class="fab fa-facebook-square fa-2x text-secondary"></em></a>
                            @endif

                            @if(eshop("social.instagram"))
                                <a href="{{ eshop("social.instagram") }}"><em class="fab fa-instagram-square fa-2x text-secondary"></em></a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <ul class="list-unstyled vstack gap-2">
                    <li>
                        <h2 class="fs-6 fw-500 mb-0 border-bottom align-self-stretch pb-2">Γενικά</h2>
                    </li>
                    <li><a href="{{ route('pages.show', [app()->getLocale(), 'terms-of-service']) }}" class="text-dark text-hover-underline">{{ __("Terms of service") }}</a></li>
                    <li><a href="{{ route('pages.show', [app()->getLocale(), 'data-protection']) }}" class="text-dark text-hover-underline">{{ __("Data protection") }}</a></li>
                    <li><a href="{{ route('pages.show', [app()->getLocale(), 'return-policy']) }}" class="text-dark text-hover-underline">{{ __("Return policy") }}</a></li>
                </ul>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <ul class="list-unstyled vstack gap-2">
                    <li>
                        <h2 class="fs-6 fw-500 mb-0 border-bottom align-self-stretch pb-2">{{ __("Support") }}</h2>
                    </li>
                    <li><a href="{{ route('pages.show', [app()->getLocale(), 'shipping-methods']) }}" class="text-dark text-hover-underline">{{ __("Shipping methods") }}</a></li>
                    <li><a href="{{ route('pages.show', [app()->getLocale(), 'payment-methods']) }}" class="text-dark text-hover-underline">{{ __("Payment methods") }}</a></li>
                </ul>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <h2 class="fs-6 fw-500 mb-2 border-bottom pb-2">{{ __("Contact") }}</h2>
                <p class="vstack gap-2">
                    <span>{{ __("company.address") }}</span>
                    <span class="p-0">{!! implode('<br>', __("company.phone")) !!}</span>
                    <span class="p-0">{{ __("company.email") }}</span>
                </p>
            </div>
        </div>
    </div>
</footer>

<div class="container-fluid bg-white border-top py-3">
    <div class="container-xxl">
        <div class="d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center gap-3">
            <small class="text-secondary">&copy; {{ now()->year }} {{ config('app.name') }}</small>
            <div><img loading="lazy" src="{{ asset('images/credit-cards.webp') }}" alt="Credit cards" class="img-fluid" width="250" height="40"></div>
        </div>
    </div>
</div>
