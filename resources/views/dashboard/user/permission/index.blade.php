@extends('eshop::dashboard.layouts.master', ['title' => 'Users'])

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ __("Users") }}</div>
@endsection

@section('main')
    <div class="col-12 col-xxl-9 mx-auto p-4 d-grid gap-4">
        <div class="d-grid gap-2">
            <a href="{{ route('users.show', $user) }}" class="text-secondary text-decoration-none"><em class="fa fa-chevron-left"></em> {{ $user->full_name }}</a>

            <h1 class="fs-3 mb-0">{{ __("Roles and permissions") }}</h1>
        </div>

        <livewire:dashboard.user.show-user-permissions :user="$user"/>
    </div>
@endsection