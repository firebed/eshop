<div x-data="{image: null}">
    <label for="variant-image-input" class="border ratio ratio-4x3 rounded" style="cursor: pointer">
        @if(isset($variant) && $variant->image && $src = $variant->image->url($product->has_watermark ? 'wm' : null))
            <img x-ref="image" src="{{ $src }}" class="img-middle rounded" alt=""/>
        @else
            <template x-if="image">
                <img x-ref="image" class="img-middle rounded" alt=""/>
            </template>

            <em x-show="!image" class="fas fa-image fa-7x text-gray-400 img-middle"></em>
        @endif
    </label>

    <input x-on:change="image = true; $nextTick(() => $refs.image.src = URL.createObjectURL($el.files[0]))" type='file' name="image" id="variant-image-input" accept="image/*" hidden/>
</div>