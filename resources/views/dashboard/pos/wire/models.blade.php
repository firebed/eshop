<div class="col-12 col-lg-7 col-xxl-8 bg-light d-flex flex-column px-0" style="height: calc(100vh - 3.5rem)">
    <div class="d-grid p-2 border-bottom shadow-sm bg-white">
        <div class="d-flex flex-nowrap table-responsive scrollbar gap-2">
            @include("eshop::dashboard.pos.partials.pos-navigation")
        </div>
    </div>

    <div class="p-3 flex-grow-1 overflow-auto scrollbar">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-3 row-cols-xxl-5 g-2">
            @if(isset($categories))
                @include("eshop::dashboard.pos.partials.pos-categories")
            @elseif(isset($products))
                @include("eshop::dashboard.pos.partials.pos-products")
            @elseif(isset($variants))
                @include("eshop::dashboard.pos.partials.pos-variants")
            @endif
        </div>
    </div>

    <div class="d-grid p-3 border-top shadow-sm bg-white">
        <div class="row g-2">
            <div class="col d-grid">
                <button x-bind:disabled="submitting" type="button" class="btn btn-warning fw-500 py-2" data-bs-toggle="offcanvas" data-bs-target="#shipping-form">
                    <em class="fas fa-map-marked-alt fs-4 text-orange-700"></em>
                    <span class="d-none d-xxl-block text-center mt-1">
                        Στοιχεία αποστολής
                    </span>
                </button>
            </div>

            <div class="col d-grid">
                <button x-bind:disabled="submitting" type="button" class="btn btn-info py-2 fw-500" data-bs-toggle="offcanvas" data-bs-target="#payment-form">
                    <em class="fas fa-money-check-alt fs-4 text-cyan-800"></em>
                    <span class="d-none d-xxl-block text-center mt-1">
                        Στοιχεία πληρωμής
                    </span>
                </button>
            </div>

            <div class="col d-grid">
                <button x-bind:disabled="submitting" type="button" class="btn btn-danger py-2 fw-500" data-bs-toggle="offcanvas" data-bs-target="#invoice-form">
                    <em class="fas fa-file-invoice fs-4 text-light"></em>
                    <span class="d-none d-xxl-block text-center mt-1">
                        Τιμολόγιο
                    </span>
                </button>
            </div>

            <div class="col d-grid">
                <button x-bind:disabled="submitting" type="submit" name="action" value="save" class="btn btn-green py-2 fw-500">
                    <em class="fas fa-save fs-4 text-light"></em>
                    <span class="d-none d-xxl-block text-center mt-1">
                        Αποθήκευση
                    </span>
                </button>
            </div>

            @if(!$editing)
                <div class="col d-grid">
                    <button x-bind:disabled="submitting" type="submit" name="action" value="saveAsOrder" class="btn btn-primary py-2 fw-500">
                        <em class="fas fa-cart-arrow-down fs-4 text-light"></em>
                        <span class="d-none d-xxl-block text-center mt-1">
                        Παραγγελία
                    </span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>