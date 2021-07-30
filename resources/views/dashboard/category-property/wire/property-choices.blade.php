<div class="table-responsive">
    <x-bs::table>
        <thead>
        <tr class="table-light">
            <td class="rounded-top">{{ __("eshop::category.name") }}</td>
            <td class="rounded-top w-4r">&nbsp;</td>
        </tr>
        </thead>

        <tbody>
        @foreach($choices as $index => $choice)
            <tr wire:key="choice-{{ $index }}">
                <td>
                    <input type="hidden" wire:model.defer="choices.{{ $index }}.id" name="choices[{{ $index }}][id]"/>
                    <x-bs::input.text wire:model.defer="choices.{{ $index }}.name" error="choices.{{ $index }}.name" id="name-{{ $index }}" autocomplete="off" name="choices[{{ $index }}][name]"/>
                </td>
                <td class="text-end align-middle">
                    <div class="d-flex">
                        <x-bs::button.link size="sm" wire:click.prevent="moveUp({{ $index }})" wire:loading.attr="disabled" wire:target="moveUp({{ $index }})" :disabled="$loop->first">
                            <em class="fas fa-chevron-up"></em>
                        </x-bs::button.link>

                        <x-bs::button.link size="sm" wire:click.prevent="moveDown({{ $index }})" wire:loading.attr="disabled" wire:target="moveDown({{ $index }})" :disabled="$loop->last">
                            <em class="fas fa-chevron-down"></em>
                        </x-bs::button.link>

                        <x-bs::button.link size="sm" wire:click.prevent="remove({{ $index }})" wire:loading.attr="disabled" wire:target="remove({{ $index }})">
                            <em class="far fa-trash-alt"></em>
                        </x-bs::button.link>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>

        <caption class="p-2">
            <x-bs::button.haze size="sm" wire:click.prevent="add()" wire:loading.attr="disabled">
                {{ __("eshop::buttons.add") }}
            </x-bs::button.haze>
        </caption>
    </x-bs::table>
</div>