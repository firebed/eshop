<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container-fluid px-0">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('vouchers.index', ['pending' => 1]) }}" class="nav-link @if(request()->boolean('pending')) fw-500 active border-bottom border-primary border-2 @endif" aria-current="page">Σε εκκρεμότητα</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @unless(request()->boolean('pending')) fw-500 active border-bottom border-primary border-2 @endunless" href="{{ route('vouchers.index') }}">Ολοκληρωμένα</a>
                </li>
            </ul>
        </div>
    </div>
</nav>