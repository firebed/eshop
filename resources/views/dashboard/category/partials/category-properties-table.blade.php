<x-bs::table>
    <thead>
    <tr class="table-light">
        <td class="rounded-top w-1r">
            <x-bs::input.checkbox wire:model="selectAll" id="select-all"/>
        </td>
        <td>{{ __('Name') }}</td>
        <td class="text-center">{{ __('Index') }}</td>
        <td class="text-center">{{ __('Restricted') }}</td>
        <td class="text-center">{{ __('Visible') }}</td>
        <td class="text-center">{{ __('Promote') }}</td>
        <td class="text-center">{{ __('Translations') }}</td>
        <td>{{ __('Created at') }}</td>
        <td class="rounded-top text-end"></td>
    </tr>
    </thead>

    <tbody>
    @forelse($properties as $property)
        <tr wire:key="row-{{ $property->id }}">
            <td class="align-middle">
                <x-bs::input.checkbox wire:model.defer="selected" id="prop-{{ $property->id }}" value="{{ $property->id }}"/>
            </td>

            <td class="align-middle">{{ $property->name }}</td>

            <td class="align-middle text-center">
                @if(!$property->isIndexed())
                    <em class="fa fa-minus-circle text-warning"></em>
                @elseif($property->isIndexSimple())
                    <x-bs::badge type="primary">{{ __('Simple') }}</x-bs::badge>
                @elseif($property->isIndexMultiple())
                    <x-bs::badge type="success">{{ __('Multiple') }}</x-bs::badge>
                @endif
            </td>

            <td class="align-middle text-center">
                @if(!$property->isValueRestricted())
                    <em class="fa fa-minus-circle text-warning"></em>
                @elseif($property->isValueRestrictionSimple())
                    <x-bs::badge type="primary">{{ __('Simple') }}</x-bs::badge>
                @elseif($property->isValueRestrictionMultiple())
                    <x-bs::badge type="success">{{ __('Multiple') }}</x-bs::badge>
                @endif
            </td>

            <td class="align-middle text-center">
                @if($property->visible)
                    <em class="fa fa-check-circle text-teal-500"></em>
                @else
                    <em class="fa fa-minus-circle text-warning"></em>
                @endif
            </td>

            <td class="align-middle text-center">
                @if($property->promote)
                    <em class="fa fa-check-circle text-teal-500"></em>
                @else
                    <em class="fa fa-minus-circle text-warning"></em>
                @endif
            </td>

            <td class="align-middle text-center">
                @if($property->translations_count === 2)
                    <x-bs::badge type="success">{{ $property->translations_count }}</x-bs::badge>
                @else
                    <x-bs::badge type="warning">{{ $property->translations_count }}/2</x-bs::badge>
                @endif
            </td>

            <td class="align-middle">{{ $property->created_at->format('d/m/Y') }}</td>

            <td class="text-end align-middle text-nowrap">
                <x-bs::button.primary size="sm" wire:click.prevent="edit({{ $property->id }})" wire:loading.attr="disabled" wire:target="editProperty({{ $property->id}})" class="shadow-none">
                    <em class="far fa-edit"></em>
                </x-bs::button.primary>

                @if($property->isValueRestricted())
                    <x-bs::button.warning size="sm" wire:click.prevent="editChoices({{ $property->id }})" wire:loading.attr="disabled" wire:target="editChoices({{ $property->id}})" class="shadow-none">
                        <em class="fas fa-code-branch"></em>
                    </x-bs::button.warning>
                @endif

                <x-bs::button-group>
                    <x-bs::button.haze wire:click.prevent="moveUp({{ $property->id }})" :disabled="$loop->first" size="sm" class="shadow-none">
                        <em class="fa fa-chevron-up"></em>
                    </x-bs::button.haze>

                    <x-bs::button.haze wire:click.prevent="moveDown({{ $property->id }})" :disabled="$loop->last" size="sm" class="shadow-none">
                        <em class="fa fa-chevron-down"></em>
                    </x-bs::button.haze>
                </x-bs::button-group>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center text-secondary fst-italic py-4">{{ __('No properties found') }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption class="px-2">{{ $properties->count() }} records</caption>
</x-bs::table>
