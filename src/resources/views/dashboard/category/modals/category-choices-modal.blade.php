<x-bs::modal wire:model.defer="showChoicesModal">
    <x-bs::modal.header>{{ __('Edit choices') }}</x-bs::modal.header>
    <x-bs::modal.body>
        <x-bs::table size="sm" borderless>
            <tbody>
            @foreach($choices as $i => $choice)
                <tr wire:key="choice-{{ $i }}">
                    <td>
                        <x-bs::input.text wire:model.defer="choices.{{ $i }}.name" size="sm"/>
                    </td>
                    <td class="align-middle">
                        <x-bs::button.haze wire:click.prevent="deleteChoice({{ $i }})" wire:loading.attr="disabled" wire:target="deleteChoice({{ $i }})" size="sm">
                            <em class="far fa-trash-alt"></em>
                        </x-bs::button.haze>

                        <x-bs::button.haze wire:click.prevent="moveChoiceUp({{ $i }})" wire:loading.attr="disabled" wire:target="moveChoiceUp({{ $i }})" size="sm">
                            <em class="fas fa-chevron-up"></em>
                        </x-bs::button.haze>

                        <x-bs::button.haze wire:click.prevent="moveChoiceDown({{ $i }})" wire:loading.attr="disabled" wire:target="moveChoiceDown({{ $i }})" size="sm">
                            <em class="fas fa-chevron-down"></em>
                        </x-bs::button.haze>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <caption>
                <x-bs::button.primary wire:click="addChoice" wire:loading.attr="disabled" wire:target="addChoice" size="sm">
                    {{ __("Add new") }}
                </x-bs::button.primary>
            </caption>
        </x-bs::table>
    </x-bs::modal.body>
    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
        <x-bs::button.primary type="submit" wire:loading.attr="disabled" wire:target="saveChoices">
            {{ __("Save") }}
        </x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
