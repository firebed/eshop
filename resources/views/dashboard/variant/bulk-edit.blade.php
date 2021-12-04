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
                    <h2 class="fs-5 fw-500 mb-4">{{ __('eshop::variant.bulk_edit') }}</h2>

                    <form action="{{ route('variants.bulk-update', $product) }}" method="post" x-on:submit="submitting = true">
                        @csrf
                        @method('put')

                        @foreach($properties as $property)
                            <input type="hidden" name="properties[]" value="{{ $property }}">
                        @endforeach

                        <div class="table-responsive">
                            <x-bs::table hover>
                                <thead>
                                <tr>
                                    <th>{{ __('eshop::product.variant_options') }}</th>

                                    @if(empty($properties) || in_array('price', $properties))
                                        <th>{{ __('eshop::product.price') }}</th>
                                    @endif

                                    @if(empty($properties) || in_array('compare_price', $properties))
                                        <th>{{ __('eshop::product.compare_price') }}</th>
                                    @endif

                                    @if(empty($properties) || in_array('discount', $properties))
                                        <th>{{ __('eshop::product.discount') }}</th>
                                    @endif

                                    @if(empty($properties) || in_array('sku', $properties))
                                        <th>{{ __('eshop::product.sku') }}</th>
                                    @endif

                                    @if(empty($properties) || in_array('stock', $properties))
                                        <th>{{ __('eshop::product.stock') }}</th>
                                    @endif

                                    @if(empty($properties) || in_array('weight', $properties))
                                        <th>{{ __('eshop::product.weight') }}</th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($variants as $i => $variant)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $variant->optionValues(' / ') }}</td>

                                        <td class="d-none">
                                            <input type="text" name="bulk_ids[]" value="{{ $variant->id }}">
                                        </td>

                                        @if(empty($properties) || in_array('price', $properties))
                                            <td x-data="{ v: {{ old("bulk_price.$i", $variant->price ?? 0) }} }" class="text-end w-7r">
                                                <x-eshop::money x-effect="v = value" value="v" class="text-end" error="bulk_price.{{ $i }}"/>
                                                <input x-model="v" type="text" name="bulk_price[]" hidden>
                                            </td>
                                        @endif

                                        @if(empty($properties) || in_array('compare_price', $properties))
                                            <td x-data="{ v: {{ old("bulk_compare_price.$i", $variant->compare_price ?? 0) }} }" class="text-end w-7r">
                                                <x-eshop::money x-effect="v = value" value="v" class="text-end" error="bulk_compare_price.{{ $i }}"/>
                                                <input x-model="v" type="text" name="bulk_compare_price[]" hidden>
                                            </td>
                                        @endif

                                        @if(empty($properties) || in_array('discount', $properties))
                                            <td x-data="{ v: {{ old("bulk_discount.$i", $variant->discount ?? 0) }} }" class="text-end w-7r">
                                                <x-eshop::percentage x-effect="v = value" value="v" class="text-end" error="bulk_discount.{{ $i }}"/>
                                                <input x-model="v" type="text" name="bulk_discount[]" hidden>
                                            </td>
                                        @endif

                                        @if(empty($properties) || in_array('sku', $properties))
                                            <td class="text-end w-10r">
                                                <x-bs::input.text name="bulk_sku[]" value="{{ old('bulk_sku.'.$i, $variant->sku ?? '') }}" error="bulk_sku.{{ $i }}"/>
                                            </td>
                                        @endif

                                        @if(empty($properties) || in_array('stock', $properties))
                                            <td x-data="{ v: {{ old("bulk_stock.$i", $variant->stock ?? 0)  }} }" class="text-end w-7r">
                                                <x-eshop::integer x-effect="v = value" value="v" class="text-end" error="bulk_stock.{{ $i }}"/>
                                                <input x-model="v" type="text" name="bulk_stock[]" hidden>
                                            </td>
                                        @endif

                                        @if(empty($properties) || in_array('weight', $properties))
                                            <td x-data="{ v: {{ old("bulk_weight.$i", $variant->weight ?? 0) }} }" class="text-end w-7r">
                                                <x-eshop::integer x-effect="v = value" value="v" class="text-end" error="bulk_weight.{{ $i }}" currencySymbol=" gr"/>
                                                <input x-model="v" type="text" name="bulk_weight[]" hidden>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </x-bs::table>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <x-bs::button.primary x-bind:disabled="submitting" type="submit">
                                <em x-cloak x-show="submitting" class="fa fa-circle-notch fa-spin me-2"></em>
                                {{ __('Save') }}
                            </x-bs::button.primary>
                        </div>
                    </form>
                </x-bs::card.body>
            </x-bs::card>
        </div>
    </div>
@endsection
