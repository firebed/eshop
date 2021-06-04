@extends('dashboard.layouts.dashboard', ['title' => 'Users'])

@section('main')
    @livewire('dashboard.user.show-users')
@endsection
