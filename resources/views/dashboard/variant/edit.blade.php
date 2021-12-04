@extends('eshop::dashboard.layouts.product', ['product' => $variant->parent])

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
        <div class="col-12 col-lg-4 d-block">
            <livewire:dashboard.product.variants-table :product="$product"/>
        </div>

        <div class="col-12 col-lg-8">
            <form action="{{ route('variants.update', $variant) }}" enctype="multipart/form-data" method="post" class="vstack gap-4"
                  x-data='{ submitting: false }'
                  x-on:submit="submitting = true"
            >
                @csrf
                @method('put')

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h1 class="fs-4 mb-0">{{ $variant->option_values }}</h1>

                    <div class="d-flex justify-content-end gap-1 flex-grow-1">
                        <div class="btn-group align-self-start" role="group">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#label-print-dialog" type="button">
                                <em class="fas fa-print"></em> {{ __("Labels") }}
                            </button>
                        </div>

                        <button class="btn btn-outline-primary btn-sm" type="submit" x-bind:disabled="submitting">
                            <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></em>
                            <em x-show="!submitting" class="fa fa-save"></em>
                            {{ __("Save") }}
                        </button>
                    </div>
                </div>

                <x-bs::card>
                    <x-bs::card.body>
                        <div class="row row-cols-1 row-cols-sm-2 g-4">
                            <div class="col col-sm-7 col-md-8">
                                @include('eshop::dashboard.variant.partials.options')
                            </div>

                            <div class="col col-sm-5 col-md-4">
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
            </form>

            <div class="mt-4">
                @include('eshop::dashboard.variant.partials.variant-delete-form')
            </div>

            <form action="{{ route('labels.export') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="labels[0][product_id]" value="{{ $variant->id }}">
                <input type="hidden" name="labels[0][quantity]" value="1">
                <x-label-printer-dialog id="label-print-dialog"/>
            </form>
        </div>
    </div>
@endsection
