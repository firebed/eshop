<x-bs::table hover>
    <thead>
    <tr class="table-light text-nowrap">
        <td class="w-1r rounded-top">
            <x-bs::input.checkbox wire:model="selectAll" id="check-all"/>
        </td>
        <x-bs::table.heading class="w-6r">{{ __("Country") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('region')" :direction="$sortField === 'region' ? $sortDirection : null">{{ __("Region") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('type')" :direction="$sortField === 'type' ? $sortDirection : null">{{ __("Type") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('courier_store')" :direction="$sortField === 'courier_store' ? $sortDirection : null">{{ __("Store") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('courier_county')" :direction="$sortField === 'courier_county' ? $sortDirection : null">{{ __("County") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('courier_address')" :direction="$sortField === 'courier_address' ? $sortDirection : null">{{ __("Address") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('courier_phone')" :direction="$sortField === 'courier_phone' ? $sortDirection : null">{{ __("Phone") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('postcode')" :direction="$sortField === 'postcode' ? $sortDirection : null">{{ __("Postcode") }}</x-bs::table.heading>
        <x-bs::table.heading class="rounded-top"/>
    </tr>
    </thead>

    <tbody>
    @forelse($inaccessibleAreas as $area)
        <tr wire:key="row-{{ $area->id }}">
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" value="{{ $area->id }}" id="cb-{{ $area->id }}"/>
            </td>
            <td class="align-middle">{{ $area->country->name }}</td>
            <td class="align-middle">{{ $area->region }}</td>
            <td class="align-middle">{{ $area->type }}</td>
            <td class="align-middle">{{ $area->courier_store }}</td>
            <td class="align-middle">{{ $area->courier_county }}</td>
            <td class="align-middle">{{ $area->courier_address }}</td>
            <td class="align-middle">{{ $area->courier_phone }}</td>
            <td class="align-middle">{{ $area->postcode }}</td>
            <td class="text-end">
                <a href="#" wire:click.prevent="edit({{ $area->id }})" class="text-decoration-none"><em class="fas fa-pen"></em></a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="20" class="text-center py-4 fst-italic text-secondary">{{ __('No records found') }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::wire-pagination :paginator="$inaccessibleAreas"/>
    </caption>
</x-bs::table>
