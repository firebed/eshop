@extends('eshop::customer.layouts.master', ['title' =>  __('My orders')])

@section('main')
    <div class="container-fluid bg-pink-500">
        <div class="container pt-4">
            <div class="row py-4">
                <div class="col fs-3 text-light">{{ user()->fullName }}</div>
            </div>
        </div>
    </div>

    @include('eshop::customer.account.partials.account-navbar')

    <div class="container-fluid py-3" @if(session('success')) x-data x-init="$dispatch('toast-notification', {type: 'success', title: '{{ session('success') }}', content: '', autohide: true})" @endif>
        <div class="container">
            <h1 class="fs-4 fw-normal mb-4">{{ __("My orders") }}</h1>

            <div class="table-responsive bg-white rounded">
                <x-bs::table hover>
                    <thead>
                    <tr>
                        <td>{{ __("ID") }}</td>
                        <td>{{ __("Status") }}</td>
                        <td>{{ __("Payment") }}</td>
                        <td>{{ __("Shipping") }}</td>
                        <td>{{ __("Total") }}</td>
                        <td>{{ __("Date") }}</td>
                        <td></td>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="align-middle">{{ $order->id }}</td>
                            <td class="align-middle">{{ __('eshop::account.order.' . $order->status->name) }}</td>
                            <td class="align-middle">{{ __('eshop::payment.' . $order->paymentMethod->name) ?? "" }}</td>
                            <td class="align-middle">{{ $order->shippingMethod->name ?? "" }}</td>
                            <td class="align-middle">{{ format_currency($order->total) }}</td>
                            <td class="align-middle">{{ $order->submitted_at->isoFormat('lll') }}</td>
                            <td class="text-end"><a href="{{ route('account.orders.show', [app()->getLocale(), $order]) }}" class="btn btn-sm btn-primary">{{ __("eshop::account.order.details") }}</a></td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>

                    <caption>
                        <x-eshop::wire-pagination :paginator="$orders"/>
                    </caption>
                </x-bs::table>
            </div>
        </div>
    </div>
@endsection
