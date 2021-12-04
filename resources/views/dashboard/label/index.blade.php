@extends('eshop::dashboard.layouts.master')

@section('header', __("Labels"))

@section('main')
    <div class="col-12 col-xxl-7 mx-auto p-4">
        <form action="{{ route('labels.export') }}" method="POST" target="_blank">
            @csrf
            <x-label-printer-dialog id="label-print-dialog"/>
            
            @livewire('dashboard.label.labels-table')
        </form>
    </div>
@endsection
