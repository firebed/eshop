@extends('eshop::customer.layouts.master', ['title' =>  __('Cart')])

@section('main')
    @dump($cart)
@endsection
