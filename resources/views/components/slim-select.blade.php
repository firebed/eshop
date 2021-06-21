@props([
    'error' => NULL,
    'allowDeselect' => false,
])

<div wire:ignore
     x-data="{ values: @entangle($attributes->wire('model')) }"
     x-init="new SlimSelect({
        select: $refs.select,
        showSearch: false,
        allowDeselect: {{ $allowDeselect ? 'true' : 'false' }},
        onChange: () => {
            selected = $refs.select.slim.selected()
            values = selected ? selected : ''
        }
     })
     $refs.select.slim.set(values)
    "
>
    <select hidden x-ref="select" {{ $attributes->whereDoesntStartWith('wire:model') }}>
        {{ $slot }}
    </select>
</div>

@if($error)
    @error($error)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
@endif
