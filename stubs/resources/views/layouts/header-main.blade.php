<div class="row align-items-center g-4 py-2">
    <h1 class="col-auto mb-0">
        <strong>
            <a id="logo" title="{{ config('app.name') }}" style="--bg: url({{ asset(config('eshop.logo')) }})" href="{{ route('home', app()->getLocale()) }}">{{ config('app.url') }}</a>
        </strong>
    </h1>

    <div class="col-auto ms-auto hstack gap-4 order-lg-2">
        <div class="d-none d-md-flex justify-content-center ms-auto">
            <em class="fa fa-mobile-alt fa-2x text-primary me-2"></em>
            <div class="small fw-500 vstack">
                @foreach(__("company.phone") as $phone)
                    <a href="tel:{{ $phone }}" class="lh-sm text-decoration-none text-dark">{{ $phone }}</a>
                @endforeach
            </div>
        </div>

        <livewire:checkout.cart-button/>
    </div>

    <div class="col-12 col-lg order-lg-1 px-lg-5">
        <form x-data="searchBar()" x-on:click.outside="close" action="{{ route('products.search.index', app()->getLocale()) }}" class="position-relative">
            <label class="d-none" for="search-bar">{{ __("Search") }}</label>
            <input x-ref="input"
                   x-on:keydown.escape="reset"
                   x-on:input.debounce="search"
                   x-on:focus="show = true" id="search-bar"
                   value="{{ request()->get('search_term', '') }}"
                   name="search_term"
                   type="search"
                   class="form-control"
                   placeholder="{{ __("I'm looking for...")}}"
                   autocomplete="off"
                   autocorrect="off"
                   autocapitalize="off">

            <div x-cloak x-show="show && results.length > 0" x-transition class="position-absolute h-5r bg-white border shadow-sm vstack w-100 rounded py-2 top-100" style="z-index: 1050">
                <template x-for="(result, i) in results" :key="i">
                    <a x-bind:href="result.href" class="text-decoration-none list-group-item-action text-dark px-3 py-2" x-html="result.text"></a>
                </template>
            </div>
        </form>
    </div>
</div>

@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchBar', () => ({
                show: false,
                results: [],

                open() {
                    this.show = true
                },

                close() {
                    this.show = false
                },

                reset() {
                    this.results = [];
                    this.close()
                },

                search() {
                    const action = '{{ route('products.search.ajax', app()->getLocale()) }}'
                    const search_term = this.$refs.input.value.trim()
                    if (search_term.length > 0) {
                        axios.post(action, {search_term}).then(res => {
                            this.results = res.data
                            this.open()
                        })
                    } else {
                        this.reset()
                    }
                }
            }))
        })
    </script>
@endpush