<div>
    <div class="d-flex mb-3 gap-3 justify-content-end">
        @foreach($locales as $key => $locale)
            <button wire:click="translateAll('{{ $key }}')" wire:loading.attr="disabled" class="btn btn-sm btn-white">
                <em class="fas fa-language text-cyan-600"></em>
                Μετάφραση όλων ({{ $locale }})
            </button>
        @endforeach

        <button wire:click="save" wire:loading.attr="disabled" class="btn btn-sm btn-primary">
            <em class="fas fa-save"></em>
            {{ __("Save") }}
        </button>
    </div>

    <div class="vstack gap-4">
        @include('eshop::dashboard.product-translation.wire.partials.product-translations')
        @include('eshop::dashboard.product-translation.wire.partials.product-seo-translations')
        @include('eshop::dashboard.product-translation.wire.partials.variants-types-translations')
        @include('eshop::dashboard.product-translation.wire.partials.variants-options-translations')
        @include('eshop::dashboard.product-translation.wire.partials.variants-seo-translations')
    </div>
</div>