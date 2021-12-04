<div class="offcanvas offcanvas-end" tabindex="-1" {{ $attributes }}>
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ __("Print labels") }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="d-grid gap-3">
            <x-bs::input.group for="label-page-width" label="{{ __('Width') }}" inline>
                <div class="input-group">
                    <input type="text" value="{{ $label->width() }}" name="width" class="form-control" aria-label="{{ __("Width") }}">
                    <span class="input-group-text">mm</span>
                </div>
            </x-bs::input.group>

            <x-bs::input.group for="label-page-height" label="{{ __('Height') }}" inline>
                <div class="input-group">
                    <input type="text" value="{{ $label->height() }}" name="height" class="form-control" id="label-page-height">
                    <span class="input-group-text">mm</span>
                </div>
            </x-bs::input.group>

            <x-bs::input.group for="label-page-margin" label="{{ __('Margin') }}" inline>
                <div class="input-group">
                    <input type="text" value="{{ $label->margin() }}" name="margin" class="form-control" id="label-page-margin">
                    <span class="input-group-text">mm</span>
                </div>
            </x-bs::input.group>

            <x-bs::input.group for="label-font-size" label="{{ __('Font size') }}" inline>
                <div class="input-group">
                    <input type="text" value="{{ $label->fontSize() }}" name="fontSize" class="form-control" id="label-font-size">
                    <span class="input-group-text">px</span>
                </div>
            </x-bs::input.group>

            <x-bs::input.group for="label-copies" label="{{ __('Copies') }}" inline>
                <x-eshop::integer name="copies" id="label-copies" value="1"/>
            </x-bs::input.group>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary"><em class="fas fa-print me-2"></em>{{ __("Print") }}</button>
            </div>
        </div>
    </div>
</div>