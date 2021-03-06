@extends('eshop::dashboard.layouts.product', ['col' => 'col-xxl-10'])

@section('content')
    <div class="row justify-content-between">
        <div class="col-5">
            <livewire:dashboard.product.variants-table :product="$product"/>
        </div>

        <div class="col-7" x-data='{ submitting: false }'>
            <x-bs::card>
                <x-bs::card.body>
                    <h2 class="fs-5 fw-500">{{ __('eshop::variant.bulk_create') }}</h2>
                    <form action="{{ route('variants.bulk-create', $product) }}" method="post" x-on:submit="submitting = true">
                        @csrf

                        <livewire:dashboard.variant.variant-bulk-create
                            :productPrice="$product->price"
                            :productSku="$product->sku"
                            :variantTypes="$variantTypes"
                            :variants="old('variants', [])"/>

                        <div class="d-flex justify-content-end">
                            <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                                <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                                {{ __('eshop::variant.buttons.save') }}
                            </x-bs::button.primary>
                        </div>
                    </form>
                </x-bs::card.body>
            </x-bs::card>
        </div>
@endsection
