@extends('eshop::dashboard.layouts.master')

@section('main')
    <div class="col-5 mx-auto py-5">
        <div class="hstack justify-content-between gap-3 mb-4">
            <h1 class="fs-3 mb-0">{{ __("Edit payment method") }}</h1>

            <div class="hstack gap-3">
                <x-bs::dropdown>
                    <button class="btn btn-secondary rounded-circle" data-bs-toggle="dropdown">
                        <em class="fas fa-bars"></em>
                    </button>

                    <x-bs::dropdown.menu button="options" alignment="right">
                        <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#delete-modal"><em class="far fa-trash-alt me-2"></em>{{ __("Delete") }}</x-bs::dropdown.item>
                    </x-bs::dropdown.menu>
                </x-bs::dropdown>

                <a href="{{ route('payment-methods.create') }}" class="btn btn-primary rounded-circle shadow-sm">
                    <em class="fas fa-plus"></em>
                </a>
            </div>
        </div>

        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('payment-methods.update', $paymentMethod) }}" method="post" class="mb-4">
            @csrf
            @method('put')

            <x-bs::card>
                <x-bs::card.body class="vstack gap-3">
                    <x-bs::input.floating-label for="name" label="{{ __('Name') }}">
                        <x-bs::input.text value="{{ old('name', $paymentMethod->name) }}" name="name" error="name" id="name" placeholder="{{ __('Name') }}"/>
                    </x-bs::input.floating-label>

                    <x-bs::input.checkbox id="show-total-on-order-form" name="show_total_on_order" :checked="old('show_total_on_order', $paymentMethod->show_total_on_order_form)">
                        {{ __('Show total on order form') }}
                    </x-bs::input.checkbox>

                    <div>
                        <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                            <span x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></span>
                            {{ __("Save") }}
                        </x-bs::button.primary>
                    </div>
                </x-bs::card.body>
            </x-bs::card>
        </form>

        <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('payment-methods.destroy', $paymentMethod) }}" method="post">
            @csrf
            @method('delete')

            <div class="modal fade" id="delete-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __("Delete") }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Διαγραφή τρόπου πληρωμής "{{ $paymentMethod->name }}";
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("eshop::buttons.cancel") }}</button>

                            <x-bs::button.danger x-bind:disabled="submitting" type="submit">
                                <span x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></span>
                                {{ __("eshop::buttons.delete") }}
                            </x-bs::button.danger>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
