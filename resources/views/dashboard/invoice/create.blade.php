@extends('eshop::dashboard.layouts.master')

@section('header', __("Invoices"))

@section('main')
    <div class="col-12 p-4" style="height: calc(100vh - 3.5rem)">
        <form action="{{ route('invoices.store') }}" method="post" class="d-flex flex-column gap-4">
            @csrf
            
            @include('eshop::dashboard.invoice.partials.invoice-header')
            @include('eshop::dashboard.invoice.partials.invoice-clients-search')

            @livewire('dashboard.invoice.rows')
            @livewire('dashboard.invoice.search-products')
        </form>
    </div>
@endsection
