@php($phones = Lang::has("company.phone") ? __('company.phone') : [])

<div class="row align-items-center g-4 py-2">
    @if(Route::currentRouteName() === 'landing_page' || Route::currentRouteName() === 'home')
        <h1 class="col-auto mb-0">
            <strong>
                <a id="logo" title="{{ config('app.name') }}" href="{{ route('home', app()->getLocale()) }}">{{ config('app.name') }}</a>
            </strong>
        </h1>
    @else
        <div class="col-auto">
            <a id="logo" title="{{ config('app.name') }}" href="{{ route('home', app()->getLocale()) }}">{{ config('app.name') }}</a>
        </div>
    @endif

    <div class="col-auto ms-auto hstack gap-4 order-lg-2">
        <div class="d-none d-md-flex justify-content-center ms-auto">
            <em class="fa fa-mobile-alt fa-2x text-primary me-2"></em>
            <div class="small fw-500 vstack">
                @foreach($phones as $phone)
                    <a href="tel:{{ telephone($phone) }}" class="lh-sm text-decoration-none text-dark">{{ $phone }}</a>
                @endforeach
            </div>
        </div>

        <livewire:checkout.cart-button/>
    </div>

    <div class="col-12 col-lg order-lg-1 px-lg-5">
        <form x-data="searchBar()" x-on:click.outside="close" x-on:submit="if(search_term.trim().length === 0) $event.preventDefault()" action="{{ route('products.search.index', app()->getLocale()) }}" class="position-relative">
            <label class="d-none" for="search-bar">{{ __("Search") }}</label>
            <input x-ref="input"
                   x-model="search_term"
                   x-on:keydown.escape="reset"
                   x-on:input.debounce="search"
                   x-on:focus="show = true" 
                   id="search-bar"
                   value="{{ request('search_term', '') }}"
                   name="search_term"
                   type="search"
                   class="form-control @if(eshop('search_bar_size') === 'lg') form-control-lg @endif"
                   placeholder="{{ __("I'm looking for...")}}"
                   autocomplete="off"
                   autocorrect="off"
                   autocapitalize="off">

            <div x-cloak x-show="show && results.length > 0" x-transition class="position-absolute h-5r bg-white border shadow-sm vstack w-100 rounded py-2 top-100" style="z-index: 2050">
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
                search_term: '{{ request()->get('search_term', '') }}',
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
