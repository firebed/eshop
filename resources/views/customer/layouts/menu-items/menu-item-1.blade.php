<li class="nav-item">
    <a class="nav-link ps-lg-0 {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home', app()->getLocale()) }}">
{{--        <em class="d-none d-lg-inline fas fa-home"></em>--}}
        <span>Αρχική</span>
    </a>
</li>