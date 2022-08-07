@extends('eshop::dashboard.layouts.master')

@section('header')
    <div class="fw-500 fs-5 mb-0">{{ $channel->name }}</div>
@endsection

@section('main')    
    <livewire:dashboard.channel.show-channel :channel="$channel"/>
@endsection
