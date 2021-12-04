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
    <livewire:dashboard.product.variants-table :product="$product"/>
@endsection
