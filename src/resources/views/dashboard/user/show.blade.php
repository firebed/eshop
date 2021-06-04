@extends('dashboard.layouts.dashboard', ['title' => 'Users'])

@section('main')
    <div class="col-12 col-xxl-9 mx-auto p-4 d-grid gap-4">
        <div class="d-grid gap-2">
            <a href="{{ route('users.index') }}" class="text-secondary text-decoration-none"><em class="fa fa-chevron-left"></em> {{ __("All users") }}</a>

            <h1 class="fs-3 mb-0">{{ $user->full_name }}</h1>
        </div>

        <div class="row row-cols-1 row-cols-lg-2 g-4">
            <div class="col">
                @include('dashboard.dashboard.user.partials.user-card')
            </div>

            <div class="col">
                <x-bs::card class="h-100">
                    <x-bs::card.body>
                        <div class="fs-5 fw-500 mb-3">{{ __('Addresses') }}</div>

                        <div class="table-responsive">
                            @livewire('dashboard.user.user-addresses-table', compact('user'))
                        </div>
                    </x-bs::card.body>
                </x-bs::card>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
            <div class="col">
                <x-bs::card>
                    <x-bs::card.body class="d-grid">
                        <div class="d-flex justify-content-between">
                            <em class="fas fa-cart-arrow-down fa-2x text-blue-400"></em>
                            <div class="fs-5">{{ $user->carts_count }} / {{ format_currency($user->carts_sum_total) }}</div>
                        </div>

                        <div class="small text-secondary text-end">{{ __('Submitted orders') }}</div>
                    </x-bs::card.body>
                </x-bs::card>
            </div>

            <div class="col">
                <x-bs::card>
                    <x-bs::card.body class="d-grid">
                        <div class="d-flex justify-content-between">
                            <em class="fas fa-ban fa-2x text-red-300"></em>
                            <div class="fs-5">{{ $user->cancelled_carts_count + $user->rejected_carts_count }} / {{ format_currency($user->cancelled_carts_sum_total + $user->rejected_carts_sum_total) }}</div>
                        </div>

                        <div class="small text-secondary text-end">{{ __('Cancelled / Rejected orders') }}</div>
                    </x-bs::card.body>
                </x-bs::card>
            </div>

            <div class="col">
                <x-bs::card>
                    <x-bs::card.body class="d-grid">
                        <div class="d-flex justify-content-between">
                            <em class="fas fa-exchange-alt fa-2x text-red-300"></em>
                            <div class="fs-5">{{ $user->returned_carts_count }} / {{ format_currency($user->returned_carts_sum_total) }}</div>
                        </div>

                        <div class="small text-secondary text-end">{{ __('Returned orders') }}</div>
                    </x-bs::card.body>
                </x-bs::card>
            </div>

            <div class="col">
                <x-bs::card>
                    <x-bs::card.body class="d-grid">
                        <div class="d-flex justify-content-between">
                            <em class="fas fa-cart-plus fa-2x text-teal-400"></em>
                            <div class="fs-5">{{ $user->a }} / {{ format_currency($user->b) }}</div>
                        </div>

                        <div class="small text-secondary text-end">{{ __('Totals') }}</div>
                    </x-bs::card.body>
                </x-bs::card>
            </div>
        </div>

        <x-bs::card>
            <x-bs::card.body>
                <div class="fs-5 fw-500 mb-3">{{ __("Orders") }}</div>

                <div class="table-responsive">
                    @livewire('dashboard.user.user-carts-table', compact('user'))
                </div>
            </x-bs::card.body>
        </x-bs::card>
    </div>
@endsection
