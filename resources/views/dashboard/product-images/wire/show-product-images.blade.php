<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-grid gap-2">
        <a href="{{ route('products.edit', $product) }}" class="text-secondary text-decoration-none"><i class="fa fa-chevron-left"></i> {{ $product->name }}</a>

        <div class="d-flex align-items-center justify-content-between">
            <h1 class="fs-3 mb-0">{{ __("Images") }}</h1>

            <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled" wire:target="uploads, save">
                <em wire:loading.remove wire:target="save" class="fa fa-save me-2"></em>
                <em wire:loading wire:target="save" class="fa fa-spinner fa-spin me-2"></em>
                {{ __("Save") }}
            </button>
        </div>
    </div>

    <div class="d-flex gap-4">
        @if(Route::has('customer.products.show'))
            <a href="{{ route('customer.products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-secondary text-decoration-none"><i class="fa fa-eye"></i> {{ __("View") }}</a>
        @endif

        <a href="#" class="text-secondary text-decoration-none"><em class="fa fa-chart-bar"></em> {{ __("Analytics") }}</a>
        <a href="{{ route('products.variants.index', $product) }}" class="text-secondary text-decoration-none"><em class="fa fa-sitemap"></em> {{ __("Variants") }}</a>

        @if(Route::has('products.images.index'))
            <a href="{{ route('products.images.index', $product) }}" class="text-secondary text-decoration-none me-4"><i class="far fa-images"></i> {{ __("Images") }}</a>
        @endif
    </div>

    @error('uploads.*')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="d-flex gap-4 align-items-center">
        <div>
            <x-bs::input.file wire:model="uploads" wire:loading.attr="disabled" multiple accept="image/*"/>
        </div>
        <div wire:loading wire:target="uploads" class="text-secondary">
            <em class="fa fa-spinner fa-spin me-2"></em>{{ __("Please wait...") }}
        </div>
        @if(filled($uploads))
            <div wire:loading.remove="" class="fw-bold text-pink-500">{{ __("Images were uploaded! Please click the save button to save the changes.") }}</div>
        @endif
    </div>

    <x-bs::card>
        @include('eshop::dashboard.product-images.partials.product-images-table')
    </x-bs::card>
</div>