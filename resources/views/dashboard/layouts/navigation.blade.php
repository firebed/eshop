@php($bg = "rgb(49, 58, 70)")
@php($collapsed = Cache::get('dashboard-sidebar-collapsed.' . auth()->id(), false))

<div x-data="{ collapsed: {{ $collapsed ? 'true' : 'false' }} }" x-bind:class="{ 'collapsed': collapsed }"
     x-on:toggle-collapse.window="collapsed = !collapsed; axios.put('/dashboard/sidebar')"
     id="dashboard-nav" 
     class="col-12 col-xl-auto w-xl-17r p-0 m-0 vh-xl-100 sticky-top shadow {{ $collapsed ? 'collapsed' : '' }}">
    
    <nav class="navbar navbar-expand-xl navbar-dark p-xl-0 align-items-xl-start vh-xl-100" style="background-color: {{ $bg }}">
        <div class="container-fluid align-items-xl-start flex-xl-column h-100 px-xl-0">
            <div class="d-flex align-items-center w-xl-100 px-xl-2 text-decoration-none justify-content-xl-between" style="min-height: 3.5rem !important;">
                <div class="brand d-flex align-items-center ps-xl-3">
                    <em class="fas fa-store text-pink-500"></em>
                    <span class="fs-5 text-light">{{ config('app.name') }}</span>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboard-offcanvas" aria-controls="dashboard-offcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-start h-100" tabindex="-1" id="dashboard-offcanvas" aria-labelledby="brand" style="background-color: {{ $bg }};">
                <div class="offcanvas-header">
                    <div class="h5 mb-0 offcanvas-title text-light" id="brand">{{ config('app.name') }}</div>
                    <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body p-0">
                    <ul class="navbar-nav flex-column flex-grow-1 px-2 hide-scrollbar">
                        @include('eshop::dashboard.layouts.menu-items')
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
