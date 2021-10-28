@extends('eshop::dashboard.layouts.master', ['title' => 'Users'])

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Users") }}</div>
@endsection

@section('main')
    <livewire:dashboard.user.show-users/>
@endsection
