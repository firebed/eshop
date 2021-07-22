<x-bs::sidebar company="{{ config('app.name') }}">
{{--    <a href="#" @if(request()->routeIs('dashboard')) class="bg-gray-700" @endif><i class="fas fa-tachometer-alt w-1r me-2"></i> {{ __("Dashboard") }}</a>--}}
{{--    <hr class="my-2">--}}
    @can('View products')
        <a href="{{ route('products.index') }}" @if(request()->routeIs('products.*')) class="bg-gray-700" @endif><em class="fas fa-boxes w-1r me-2 text-teal-500"></em>{{ __("Products") }}</a>
    @endcan

    @can('View orders')
        <a href="{{ route('carts.index') }}" @if(request()->routeIs('carts.*')) class="bg-gray-700" @endif><em class="fas fa-shopping-cart w-1r me-2 text-cyan-300"></em>{{ __("Orders") }}</a>
    @endcan

    <hr class="my-2">

    @can('View categories')
        <a href="{{ route('categories.index') }}" @if(request()->routeIs('categories.*')) class="bg-gray-700" @endif><i class="fas fa-list-ul w-1r me-2 text-teal-500"></i>{{ __("Categories") }}</a>
    @endcan

    @can('View manufacturers')
        <a href="{{ route('manufacturers.index') }}" @if(request()->routeIs('manufacturers.*')) class="bg-gray-700" @endif><i class="fas fa-industry w-1r me-2 text-teal-500"></i>{{ __("Manufacturers") }}</a>
    @endcan

    @can('View collections')
        <a href="{{ route('collections.index') }}" @if(request()->routeIs('collections.*')) class="bg-gray-700" @endif><em class="fas fa-layer-group w-1r me-2 text-pink-500"></em>{{ __("eshop::collection.collections") }}</a>
    @endcan

    @can('View countries')
        <a href="{{ route('countries.index') }}" @if(request()->routeIs('countries.index')) class="bg-gray-700" @endif><i class="fas fa-flag w-1r me-2 text-cyan-300"></i>{{ __("Countries") }}</a>
    @endcan

    @can('View translations')
{{--        <a href="#" @if(request()->routeIs('localization')) class="bg-gray-700" @endif><i class="fa fa-language w-1r me-2 text-pink-400"></i> {{ __("Translations") }}</a>--}}
    @endcan

    @can('View shipping methods')
        <a href="{{ route('shipping-methods.index') }}" @if(request()->routeIs('shipping-methods.index')) class="bg-gray-700" @endif><i class="fas fa-shipping-fast w-1r me-2 text-cyan-300"></i>{{ __("Shipping methods") }}</a>
    @endcan

    @can('View payment methods')
        <a href="{{ route('payment-methods.index') }}" @if(request()->routeIs('payment-methods.index')) class="bg-gray-700" @endif><i class="fas fa-hand-holding-usd w-1r me-2 text-cyan-300"></i>{{ __("Payment methods") }}</a>
    @endcan

    @can('View users')
        <a href="{{ route('users.index') }}" @if(request()->routeIs('users.index')) class="bg-gray-700" @endif><i class="fas fa-users w-1r me-2 text-purple-300"></i>{{ __("Users") }}</a>
    @endcan

    <hr class="my-2">

{{--    <a href="#" @if(request()->routeIs('store')) class="bg-gray-700" @endif><i class="fas fa-store w-1r me-2 text-blue-300"></i> {{ __("Store") }}</a>--}}

{{--    <a href="#" @if(request()->routeIs('marketing')) class="bg-gray-700" @endif><i class="fa fa-lightbulb w-1r me-2 text-yellow-300"></i> {{ __("Marketing") }}</a>--}}
    <a href="{{ route('analytics') }}" @if(request()->routeIs('analytics')) class="bg-gray-700" @endif><i class="far fa-chart-bar w-1r me-2 text-pink-300"></i>{{ __("Analytics") }}</a>
{{--    <a href="#" @if(request()->routeIs('welcome-messages')) class="bg-gray-700" @endif><i class="fa fa-comment-dots w-1r me-2 text-indigo-300"></i> {{ __("Welcome messages") }}</a>--}}

    @can('Edit configuration')
        <a href="{{ route('config.index') }}" @if(request()->routeIs('config')) class="bg-gray-700" @endif><i class="fa fa-cogs w-1r me-2"></i>{{ __("Configuration") }}</a>
    @endcan
</x-bs::sidebar>
