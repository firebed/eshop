<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-xxl">
        <a class="navbar-brand d-lg-none" href="#">{{ __("Menu") }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{--                <li class="nav-item">--}}
                {{--                    <a class="nav-link active h6 mb-0 ps-lg-0" aria-current="page" href="{{ route('home', app()->getLocale()) }}">{{ __("Home") }}</a>--}}
                {{--                </li>--}}
            </ul>
            <div class="d-flex">
                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">{{ __("Track your order") }}</a>
            </div>
        </div>
    </div>
</nav>