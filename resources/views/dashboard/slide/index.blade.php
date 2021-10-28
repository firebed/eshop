@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Διαφάνειες") }}</div>
@endsection

@section('main')
    @livewire('dashboard.slide.show-slides')
@endsection
