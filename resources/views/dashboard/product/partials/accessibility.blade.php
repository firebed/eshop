<div class="card shadow-sm">
    <div x-data="{ has_variants: {{ isset($product) && $product->has_variants ? 'true' : 'false' }} }" x-on:updated-variant-types.window="has_variants = ($event.detail > 0)" class="card-body">
        <div class="fw-500 mb-3">{{ __("Accessibility") }}</div>

        <div class="d-grid">
            <x-bs::input.switch name="visible" :checked="old('visible', $product->visible ?? true)" id="visible">
                {{ __("Customers can view this product") }}
            </x-bs::input.switch>

            <x-bs::input.switch name="available" :checked="old('available', $product->available ?? true)" id="available">
                {{ __("Customers can purchase this product") }}
            </x-bs::input.switch>

            <x-bs::input.switch name="display_stock" :checked="old('display_stock', $product->display_stock ?? true)" id="display-stock">
                {{ __("Customers can see the available stock") }}
            </x-bs::input.switch>

            <x-bs::input.switch name="recent" :checked="old('recent', $product->recent ?? false)" id="recent">
                {{ __("Display new label") }}
            </x-bs::input.switch>

            <x-bs::input.switch name="has_watermark" :checked="old('has_watermark', $product->has_watermark ?? false)" id="has-watermark">
                {{ __("Εμφάνιση αρχικής εικόνας με watermark") }}
            </x-bs::input.switch>

            <x-bs::input.switch name="promote" :checked="old('promote', $product->promote ?? false)" id="promote">
                {{ __("Προώθηση προϊόντος") }}
            </x-bs::input.switch>

            <div x-show="!has_variants" x-transition>
                <div class="row mt-3">
                    <div x-data="{ number: '{{ old('available_gt', isset($product) ? $product->available_gt : 0) }}' }" class="col">
                        <label for="available-gt" class="form-label">Διαθέσιμο</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text fw-500">&#8805;</span>
                            <x-eshop::integer x-effect="number = value" :value="'number'" id="available-gt"/>
                        </div>

                        <input type="hidden" x-model="number" name="available_gt">
                    </div>

                    <div x-data="{ number: '{{ old('display_stock_lt', $product->display_stock_lt ?? '') }}' }" class="col">
                        <label for="display-stock-lt" class="form-label">Απόκρυψη αποθέματος</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text fw-500">&#8805;</span>
                            <x-eshop::integer x-effect="number = value" :value="'number'" id="display-stock-lt"/>
                        </div>

                        <input type="hidden" x-model="number" name="display_stock_lt">
                    </div>
                </div>

                <div class="mt-3">
                    <div class="fw-bold py-1 small">Κανάλια πώλησης</div>
                    @foreach($channels as $channel)
                        <x-bs::input.switch name="channels[]" value="{{ $channel->id }}" :checked="old('channels.'.$channel->id, isset($product) ? $product->channels->contains($channel) : true)" id="channel-{{ $channel->id }}">
                            {{ $channel->name }}
                        </x-bs::input.switch>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
