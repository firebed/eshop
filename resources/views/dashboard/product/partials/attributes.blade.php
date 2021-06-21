<div wire:loading.class="opacity-50" wire:target="product.category_id" class="card shadow-sm" style="background-color: rgb(248, 251, 252)">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Attributes") }}</div>

        <div class="d-grid gap-2">
            @forelse($props as $property)
                <x-bs::input.group for="prop-{{ $property->id }}" label="{{ $property->name }}" inline wire:key="props-{{ $property->id }}">
                    @unless($property->isValueRestricted())
                        <x-bs::input.text wire:model.defer="values.{{ $property->id }}" id="prop-{{ $property->id }}" error="values.{{ $property->id }}"/>
                    @else
                        <x-eshop::slim-select wire:model.defer="choices.{{ $property->id }}" id="prop-{{ $property->id }}" error="choices.{{ $property->id }}" allow-deselect :multiple="$property->isValueRestrictionMultiple()">
                            @foreach($property->choices as $choice)
                                <option value="{{ $choice->id }}">{{ $choice->name }}</option>
                            @endforeach
                        </x-eshop::slim-select>
                    @endunless
                </x-bs::input.group>
            @empty
                <div class="text-secondary">{{ __("No attributes found for this category.") }}</div>
            @endforelse
        </div>
    </div>
</div>
