@php($active = "bg-gray-900")
@php($link = "nav-link px-3 rounded")

{{--    <a href="#" @if(request()->routeIs('dashboard')) class="bg-gray-700" @endif><em class="fas fa-tachometer-alt me-2"></em> {{ __("Dashboard") }}</a>--}}

@can('Manage products')
    <li class="nav-item">
        <a href="{{ route('products.index') }}" @class([$link, $active => request()->routeIs('products.*')])>
            <em class="fas fa-boxes text-teal-500"></em>
            <span>{{ __("Products") }}</span>
        </a>
    </li>
@endcan

@canany(['Manage orders', 'Manage assigned orders'])
    <li class="nav-item">
        <a href="{{ route('carts.index') }}" @class([$link, $active => request()->routeIs('carts.*')])>
            <em class="fas fa-shopping-cart text-cyan-300"></em>
            <span>{{ __("Orders") }}</span>
        </a>
    </li>
@endcanany

@canany(['Manage POS', 'Create POS order'])
    <li class="nav-item">
        <a href="{{ route('pos.create') }}" @class([$link, $active => request()->routeIs('pos.*')])>
            <em class="fas fa-cash-register text-yellow-500"></em>
            <span>{{ __("POS") }}</span>
        </a>
    </li>
@endcanany

@can('Manage invoices')
    <li class="nav-item">
        <a href="{{ route('invoices.index') }}" @class([$link, $active => request()->routeIs('invoices.*')])>
            <em class="fas fa-file-invoice text-yellow-500"></em>
            <span>{{ __("Invoices") }}</span>
        </a>
    </li>
@endcan

@can('Manage categories')
    <li class="nav-item">
        <a href="{{ route('categories.index') }}" @class([$link, $active => request()->routeIs('categories.*')])>
            <em class="fas fa-list-ul text-teal-500"></em>
            <span>{{ __("Categories") }}</span>
        </a>
    </li>
@endcan

@can('Manage manufacturers')
    <li class="nav-item">
        <a href="{{ route('manufacturers.index') }}" @class([$link, $active => request()->routeIs('manufacturers.*')])>
            <em class="fas fa-industry text-teal-500"></em>
            <span>{{ __("Manufacturers") }}</span>
        </a>
    </li>
@endcan

@can('Manage products')
    <li class="nav-item">
        <a href="{{ route('labels.index') }}" @class([$link, $active => request()->routeIs('labels.*')])>
            <em class="fas fa-tags text-pink-500"></em>
            <span>{{ __("Labels") }}</span>
        </a>
    </li>
@endcan

@can('Manage collections')
    <li class="nav-item">
        <a href="{{ route('collections.index') }}" @class([$link, $active => request()->routeIs('collections.*')])>
            <em class="fas fa-layer-group text-pink-500"></em>
            <span>{{ __("eshop::collection.collections") }}</span>
        </a>
    </li>
@endcan

@can('Manage countries')
    <li class="nav-item">
        <a href="{{ route('countries.index') }}" @class([$link, $active => request()->routeIs('countries.*')])>
            <em class="fas fa-flag text-cyan-300"></em>
            <span>{{ __("Countries") }}</span>
        </a>
    </li>
@endcan

@can('Manage shipping methods')
    <li class="nav-item">
        <a href="{{ route('shipping-methods.index') }}" @class([$link, $active => request()->routeIs('shipping-methods.*')])>
            <em class="fas fa-shipping-fast text-cyan-300"></em>
            <span>{{ __("Shipping methods") }}</span>
        </a>
    </li>
@endcan

@can('Manage country shipping methods')
    <li class="nav-item">
        <a href="{{ route('country-shipping-methods.index') }}" @class([$link, $active => request()->routeIs('country-shipping-methods.*')])>
            <em class="fas fa-dolly text-cyan-300"></em>
            <span>{{ __("Shipping options") }}</span>
        </a>
    </li>
@endcan

@can('Manage payment methods')
    <li class="nav-item">
        <a href="{{ route('payment-methods.index') }}" @class([$link, $active => request()->routeIs('payment-methods.*')])>
            <em class="fas fa-hand-holding-usd text-cyan-300"></em>
            <span>{{ __("Payment methods") }}</span>
        </a>
    </li>
@endcan

@can('Manage country payment methods')
    <li class="nav-item">
        <a href="{{ route('country-payment-methods.index') }}" @class([$link, $active => request()->routeIs('country-payment-methods.*')])>
            <em class="fas fa-money-check text-cyan-300"></em>
            <span>{{ __("Payment options") }}</span>
        </a>
    </li>
@endcan

@can('Manage users')
    <li class="nav-item">
        <a href="{{ route('users.index') }}" @class([$link, $active => request()->routeIs('users.*')])>
            <em class="fas fa-users text-purple-300"></em>
            <span>{{ __("Users") }}</span>
        </a>
    </li>
@endcan

@can('Manage slides')
    <li class="nav-item">
        <a href="{{ route('slides.index') }}" @class([$link, $active => request()->routeIs('slides.*')])>
            <em class="fas fa-video text-yellow-300"></em>
            <span>{{ __("Διαφάνειες") }}</span>
        </a>
    </li>
@endcan

@can('Manage pages')
    <li class="nav-item">
        <a href="{{ route('pages.index') }}" @class([$link, $active => request()->routeIs('pages.*')])>
            <em class="far fa-file text-yellow-300"></em>
            <span>{{ __("Pages") }}</span>
        </a>
    </li>
@endcan

{{--    <a href="#" @if(request()->routeIs('store')) class="bg-gray-700" @endif><em class="fas fa-store text-blue-300"></em> {{ __("Store") }}</a>--}}

{{--    <a href="#" @if(request()->routeIs('marketing')) class="bg-gray-700" @endif><em class="fa fa-lightbulb text-yellow-300"><.em> {{ __("Marketing") }}</a>--}}
@can('View analytics')
    <li class="nav-item">
        <a href="{{ route('analytics.index') }}" @class([$link, $active => request()->routeIs('analytics.*')])>
            <em class="far fa-chart-bar text-pink-300"></em>
            <span>{{ __("Analytics") }}</span>
        </a>
    </li>
@endcan

{{--    <a href="#" @if(request()->routeIs('welcome-messages')) class="bg-gray-700" @endif><em class="fa fa-comment-dots text-indigo-300"></em> {{ __("Welcome messages") }}</a>--}}

@can('Edit configuration')
    <li class="nav-item">
        <a href="{{ route('config.index') }}" @class([$link, $active => request()->routeIs('config.*')])>
            <em class="fa fa-cogs"></em>
            <span>{{ __("Configuration") }}</span>
        </a>
    </li>
@endcan

@can('Configure simplify')
    <li class="nav-item">
        <a href="{{ route('simplify.index') }}" @class([$link, $active => request()->routeIs('simplify.*')])>
            <em class="fa fa-credit-card"></em>
            <span>{{ __("Simplify") }}</span>
        </a>
    </li>
@endcan

@can('Manage API keys')
    <li class="nav-item">
        <a href="{{ route('user-variables.index') }}" @class([$link, $active => request()->routeIs('user-variables.*')])>
            <em class="fa fa-key"></em>
            <span>{{ __("API Keys") }}</span>
        </a>
    </li>
@endcan