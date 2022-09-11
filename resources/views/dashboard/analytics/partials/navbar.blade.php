<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid align-items-baseline px-0">
        <a class="navbar-brand py-lg-0" href="{{ route('analytics.index') }}">Ανάλυση</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#analytics" aria-controls="analytics">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="analytics" aria-labelledby="analytics-label">
            <div class="offcanvas-header">
                <div class="h5 offcanvas-title" id="analytics-label">Ανάλυση</div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link py-lg-0 ps-0" href="{{ route('analytics.index') }}">Αρχική</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-lg-0" href="{{ route('analytics.orders.index') }}">Παραγγελίες</a>
                    </li>
                    @if(eshop('analytics.couriers'))
                        <li class="nav-item">
                            <a class="nav-link py-lg-0" href="{{ route('analytics.couriers.index') }}">Courier</a>
                        </li>
                    @endif
                    
                    @if(eshop('cart.abandoned.notifications', false))
                        <li class="nav-item">
                            <a class="nav-link py-lg-0" href="{{ route('analytics.abandoned-carts.index') }}">Εγκατάλειψη καλαθιών</a>
                        </li>
                    @endif
                    
                    <li class="nav-item">
                        <a class="nav-link py-lg-0" href="{{ route('analytics.warehouse.index') }}">Αποθήκη</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>