@extends('eshop::dashboard.layouts.product', ['col' => 'col-xxl-10'])

@section('content')
    <div class="row justify-content-between">
        <div class="col-5">
            <livewire:dashboard.product.variants-table :product="$product"/>
        </div>

        <div class="col-7">
            <form action="{{ route('products.variants.store', $product) }}" enctype="multipart/form-data" method="post" class="d-grid gap-4"
                  x-data='{ submitting: false }'
                  x-on:submit="submitting = true"
            >
                @csrf

                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="fs-4">{{ __('eshop::variant.new') }}</h1>

                    <x-bs::button.primary type="submit" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __("Save") }}
                    </x-bs::button.primary>
                </div>

                <x-bs::card>
                    <x-bs::card.body>
                        <div class="row">
                            <div class="col">
                                @include('eshop::dashboard.variant.partials.options')
                            </div>

                            <div class="col-4">
                                @include('eshop::dashboard.variant.partials.image')
                            </div>
                        </div>
                    </x-bs::card.body>
                </x-bs::card>

                <x-bs::card>
                    <x-bs::card.body>
                        @include('eshop::dashboard.variant.partials.pricing')
                    </x-bs::card.body>
                </x-bs::card>

                <x-bs::card>
                    <x-bs::card.body>
                        @include('eshop::dashboard.variant.partials.inventory')
                    </x-bs::card.body>
                </x-bs::card>

                <x-bs::card>
                    <x-bs::card.body>
                        @include('eshop::dashboard.variant.partials.accessibility')
                    </x-bs::card.body>
                </x-bs::card>

                <x-bs::card>
                    <x-bs::card.body>
                        @include('eshop::dashboard.variant.partials.variant-seo')
                    </x-bs::card.body>
                </x-bs::card>

                <div class="d-flex justify-content-end">
                    <x-bs::button.success type="submit" x-bind:disabled="submitting">
                        <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                        {{ __("Save") }}
                    </x-bs::button.success>
                </div>
            </form>
        </div>
@endsection
