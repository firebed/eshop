@extends('eshop::dashboard.layouts.master')

@section('header')
    <h1 class="fs-5 mb-0">Ειδοποιήσεις</h1>
@endsection

@section('main')
    <div class="col-12 col-xxl-9 mx-auto p-4 d-grid gap-3">
        @livewire('dashboard.notifications-table')
    </div>
@endsection