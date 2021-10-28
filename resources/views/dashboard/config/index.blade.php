@extends('eshop::dashboard.layouts.master', ['title' => 'Configuration'])

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Configuration") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-9 mx-auto p-4 d-grid gap-4">
        <div class="row row-cols-3 g-4">
            <div class="col">
                <livewire:dashboard.config.show-vats/>
            </div>

            <div class="col">
                <livewire:dashboard.config.show-units/>
            </div>

            <div class="col">
                <livewire:dashboard.config.show-locales/>
            </div>
        </div>
    </div>
@endsection
