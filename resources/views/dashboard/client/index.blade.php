@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Πελάτες") }}</div>
@endsection

@section('main')
    @livewire('dashboard.client.show-clients')
@endsection
