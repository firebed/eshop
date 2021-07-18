<x-bs::card style="background-color: rgb(248, 251, 252)">
    <x-bs::card.body>
        <div class="fw-500 mb-3">{{ __("Attributes") }}</div>

        <div class="d-grid gap-2" x-on:product-category-changed.window="$wire.setCategory($event.detail)">
            @forelse($props as $property)
                <x-bs::input.group wire:key="property-{{ $property->id }}" for="prop-{{ $property->id }}" label="{{ $property->name }}" inline>
                    @if($property->isValueRestricted())
                        <select
                                wire:ignore
                                x-init="new SlimSelect({select: $el, showSearch: false, allowDeselect: true })"
                                name="properties[choices][{{ $property->id }}][]"
                                hidden
                                id="prop-{{ $property->id }}"
                                @if($property->isValueRestrictionMultiple()) multiple @endif>
                        >
                            <option data-placeholder="true"></option>
                            @foreach($property->choices as $choice)
                                <option value="{{ $choice->id }}" @if(in_array($choice->id, $properties['choices'][$property->id] ?? [])) selected @endif>{{ $choice->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="properties[values][{{ $property->id }}]" value="{{ $properties['values'][$property->id] ?? "" }}" class="form-control" id="prop-{{ $property->id }}">
                    @endif
                </x-bs::input.group>
            @empty
                <div class="text-secondary">{{ __("No attributes found for this category.") }}</div>
            @endforelse
        </div>
    </x-bs::card.body>
</x-bs::card>
