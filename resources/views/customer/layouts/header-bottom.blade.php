<div class="row g-0">
    <div id="header-categories" class="col-3 d-none d-lg-block py-2">
        <div class="nav-link hstack justify-content-between text-light h-100">
            <div><em class="fas fa-bars me-3"></em>{{ __("Categories") }}</div>
            <div><em class="fas fa-chevron-down"></em></div>
        </div>
    </div>

    <div class="col ps-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid px-0">
                <a class="navbar-brand d-lg-none" href="#">{{ __("Categories") }}</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-start" tabindex="-1" id="navbar" aria-labelledby="main-navigation">
                    <div class="offcanvas-header border-bottom">
                        <div class="h5 offcanvas-title" id="main-navigation">{{ config('app.name') }}</div>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul x-data="menu()" class="navbar-nav me-auto flex-grow-1">
                            @include('eshop::customer.layouts.menu-items.home-item')
                            @includeIf('eshop::customer.layouts.menu-items.items')
                            @include('eshop::customer.layouts.menu-items.track-order')
                        </ul>

                        <div class="d-none d-lg-flex align-items-center">
                            <a id="tracking-btn" href="{{ route('order-tracking.index', app()->getLocale()) }}" class="btn btn-sm rounded-pill">{{ __("Track your order") }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('menu', () => ({
                mobile: document.body.clientWidth < 992,


                init() {
                    window.onresize = () => {
                        this.mobile = document.body.clientWidth < 992
                    }

                    this.$watch('mobile', mobile => {
                        setTimeout(() => {
                            if (mobile) {
                                this.$el.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    const showing = menu.classList.contains('show')
                                    menu.style.height = showing ? menu.scrollHeight + 'px' : 0
                                })
                            }
                        }, 310)
                    })
                },

                updateHeights(el, show) {
                    if (!this.mobile) return

                    el.style.height = show ? el.scrollHeight + 'px' : 0

                    const parent = el.parentElement.closest('.dropdown-menu');
                    if (parent) {
                        parent.style.height = (parent.scrollHeight + (show ? el.scrollHeight : -el.scrollHeight)) + 'px';
                    }
                }
            }))
        })
    </script>
@endpush