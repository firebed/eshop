@props([
    'values' => '',
    'error' => NULL,
    'allowDeselect' => false,
])

<div x-data
     x-init="new SlimSelect({
        select: $refs.select,
        showSearch: false,
        allowDeselect: {{ $allowDeselect ? 'true' : 'false' }},
        onChange: () => {
            selected = $refs.select.slim.selected()
            values = selected ? selected : ''
        }
     })
     //$refs.select.slim.set(values)
    "
>
    <select hidden x-ref="select" {{ $attributes }}>
        {{ $slot }}
    </select>
</div>

@if($error)
    @error($error)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
@endif
