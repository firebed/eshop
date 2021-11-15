@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Countries") }}</div>
@endsection

@section('main')
    <div class="col-12 col-md-8 col-xl-6 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ __("Countries") }}</h1>

            <a href="{{ route('countries.create') }}" class="btn btn-primary rounded-circle shadow-sm">
                <em class="fas fa-plus"></em>
            </a>
        </div>

        <div class="list-group shadow-sm">
            @foreach($countries as $country)
                <a href="{{ route('countries.show', $country) }}" class="py-3 list-group-item list-group-item-action d-flex justify-content-between">
                    <div class="hstack gap-2">
                        <i @class(['fas', 'fa-circle', 'text-success' => $country->visible, 'text-danger' => !$country->visible])></i>
                        <span>{{ $country->name }}</span>
                    </div>
                    <span class="text-secondary">{{ $country->code }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
