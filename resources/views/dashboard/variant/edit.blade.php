@extends('eshop::dashboard.layouts.product', ['product' => $variant->parent, 'col' => 'col-xxl-10'])

@section('content')
    <div class="row justify-content-between">
        <div class="col-12 col-lg-5 d-block">
            <livewire:dashboard.product.variants-table :product="$product"/>
        </div>

        <div class="col-12 col-lg-7">
            <form action="{{ route('variants.update', $variant) }}" enctype="multipart/form-data" method="post" class="vstack gap-4"
                  x-data='{ submitting: false }'
                  x-on:submit="submitting = true"
            >
                @csrf
                @method('put')

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h1 class="fs-4 mb-0">{{ $variant->options->pluck('pivot.name')->join('/') }}</h1>

                    <div class="btn-toolbar gap-2 ms-auto" role="toolbar">
                        <div class="btn-group">
                            <button class="btn btn-primary w-3r" type="submit" x-bind:disabled="submitting">
                                <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm" role="status"></em>
                                <em x-show="!submitting" class="fa fa-save"></em>
                            </button>
                        </div>

                        <div class="btn-group align-self-start" role="group">
                            @can('View product movements')
                                <a class="btn btn-white w-3r text-secondary" href="{{ route('products.movements.index', $variant) }}" title="Κινήσεις">
                                    <em class="fas fa-exchange-alt"></em>
                                </a>
                            @endcan

                            @can('View product audits')
                                <a class="btn btn-white w-3r text-secondary" href="{{ route('products.audits.index', $variant) }}" title="Ιστορικό αλλαγών">
                                    <em class="fas fa-history"></em>
                                </a>
                            @endcan

                            <button class="btn btn-white w-3r text-secondary" data-bs-toggle="offcanvas" data-bs-target="#label-print-dialog" type="button" title="Ετικέτες">
                                <em class="fas fa-receipt"></em>
                            </button>

                            @if(productRouteExists())
                                <a class="btn btn-white w-3r text-secondary" href="{{ productRoute($variant) }}" title="Προβολή">
                                    <em class="fas fa-eye"></em>
                                </a>
                            @endif
                        </div>
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

                @includeWhen($variant->channels->isNotEmpty(), 'eshop::dashboard.variant.partials.channel-pricing')

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
