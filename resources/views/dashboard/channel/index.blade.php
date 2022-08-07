@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Sales channels") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
        <h1 class="fs-3">{{ __('Sales channels') }}</h1>

        <div class="list-group shadow-sm">
            @foreach($channels as $channel)
                <a href="{{ route('channels.edit', $channel) }}" class="py-3 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span>{{ $channel->name }}</span>
                    <x-bs::badge>{{ $channel->products_count }}</x-bs::badge>
                </a>
            @endforeach
        </div>
    </div>
@endsection
