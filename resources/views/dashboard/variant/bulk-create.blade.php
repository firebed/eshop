@extends('eshop::dashboard.layouts.product')

@section('actions')
    <div class="btn-group">
        <a href="{{ route('products.variants.create', $product) }}" class="btn btn-primary"><em class="fa fa-plus me-2"></em> {{ __("eshop::variant.buttons.add_new") }}</a>

        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('variants.bulk-create', $product) }}"><em class="fa fa-folder-plus me-2"></em> {{ __("eshop::variant.buttons.add_many") }}</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col">
            <livewire:dashboard.product.variants-table :product="$product"/>
        </div>

        <div class="col-8" x-data='{ submitting: false }'>
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
