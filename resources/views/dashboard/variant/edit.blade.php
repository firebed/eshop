@extends('eshop::dashboard.layouts.master')

@push('footer_scripts')
    <script src="https://cdn.tiny.cloud/1/gxet4f4kiajd8ppsca5dsl1ymcncx4emhut5fer2lnijr2ic/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('main')
    <div class="col-12 col-xxl-10 mx-auto p-4 d-grid gap-3">
        @include('eshop::dashboard.variant.partials.variant-header')
        @include('eshop::dashboard.product.partials.product-navigation')

        <div class="row justify-content-between">
            <div class="col-12 col-lg-4">
                <livewire:dashboard.product.variants-table :product="$product"/>
            </div>

            <div class="col-12 col-lg-8 vstack gap-4">
                <form action="{{ route('variants.update', $variant) }}" enctype="multipart/form-data" method="post" class="d-grid gap-4"
                      x-data='{ submitting: false }'
                      x-on:submit="submitting = true"
                >
                    @csrf
                    @method('put')

                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="fs-4">{{ $variant->trademark }}</h1>

                        <x-bs::button.success type="submit" x-bind:disabled="submitting">
                            <em x-cloak x-show="submitting" class="fa fa-spinner fa-spin me-2"></em>
                            {{ __("Save") }}
                        </x-bs::button.success>
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

                <div>
                    @include('eshop::dashboard.variant.partials.variant-delete-form')
                </div>
            </div>
        </div>
    </div>
@endsection
