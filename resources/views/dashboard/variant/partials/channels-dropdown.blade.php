<x-bs::dropdown wire:ignore>
    <x-bs::dropdown.button class="btn-white" id="channels">
        <em class="fas fa-podcast"></em>
    </x-bs::dropdown.button>

    <x-bs::dropdown.menu class="shadow" button="bulk-actions" alignment="right" style="max-height: 350px; overflow-y: auto; overflow-x: hidden">
        @foreach($channels as $channel)
            <x-bs::dropdown.item x-on:click.prevent="$wire.toggleChannel({{ $channel->id }}, [...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), true)">
                <em class="fa fa-plus me-2 w-1r fa-sm"></em>
                {{ $channel->name }}
            </x-bs::dropdown.item>

            <x-bs::dropdown.item x-on:click.prevent="$wire.toggleChannel({{ $channel->id }}, [...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), false)">
                <em class="fa fa-minus me-2 w-1r fa-sm"></em>
                {{ $channel->name }}
            </x-bs::dropdown.item>

            @unless($loop->last)
                <x-bs::dropdown.divider/>
            @endunless
        @endforeach
    </x-bs::dropdown.menu>
</x-bs::dropdown>
