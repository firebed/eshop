<x-bs::table hover>
    <thead>
    <tr class="table-light text-nowrap">
        <td class="w-1r rounded-top"><x-bs::input.checkbox wire:model="selectAll" id="check-all"/></td>
        <x-bs::table.heading class="w-7r">{{ __("Image") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __("Name") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __("Created at") }}</x-bs::table.heading>
        <x-bs::table.heading class="rounded-top"/>
    </tr>
    </thead>

    <tbody>
    @forelse($manufacturers as $manufacturer)
        <tr wire:key="row-{{ $manufacturer->id }}">
            <td>
                <x-bs::input.checkbox wire:model="selected" value="{{ $manufacturer->id }}" id="cb-{{ $manufacturer->id }}"/>
            </td>
            <td>
                <div class="ratio ratio-1x1">
                    @if($manufacturer->image && $src = $manufacturer->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $manufacturer->name }}" class="img-middle rounded">
                    @endif
                </div>
            </td>
            <td>{{ $manufacturer->name }}</td>
            <td>{{ __($manufacturer->created_at->format('d/m/Y')) }}</td>
            <td class="text-end">
                <a href="#" wire:click.prevent="edit({{ $manufacturer->id }})" class="text-decoration-none">{{ __("Edit") }}</a>
            </td>
        </tr>
    @empty
        <tr wire:key="no-records-found">
            <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$manufacturers"/>
    </caption>
</x-bs::table>
