<x-bs::table hover>
    <thead>
    <tr class="table-light text-nowrap">
        <td class="w-1r rounded-top"><x-bs::input.checkbox wire:model="selectAll" id="check-all"/></td>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('visible')" :direction="$sortField === 'visible' ? $sortDirection : null" class="w-6r">{{ __("Visible") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __("Name") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('code')" :direction="$sortField === 'code' ? $sortDirection : null">{{ __("ISO code") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('timezone')" :direction="$sortField === 'timezone' ? $sortDirection : null">{{ __("Timezone") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __("Created at") }}</x-bs::table.heading>
        <x-bs::table.heading class="rounded-top"/>
    </tr>
    </thead>

    <tbody>
    @forelse($countries as $country)
        <tr wire:key="row-{{ $country->id }}">
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" value="{{ $country->id }}" id="cb-{{ $country->id }}"/>
            </td>
            <td class="align-middle">
                @if($country->visible)
                    <em class="fa fa-check-circle text-teal-500"></em>
                @else
                    <em class="fa fa-minus-circle text-warning"></em>
                @endif
            </td>
            <td class="align-middle">{{ $country->name }}</td>
            <td class="align-middle">{{ $country->code }}</td>
            <td class="align-middle">{{ $country->timezone }}</td>
            <td>{{ __($country->created_at->format('d/m/Y')) }}</td>
            <td class="text-end">
                <a href="#" wire:click.prevent="edit({{ $country->id }})" class="text-decoration-none">{{ __("Edit") }}</a>
            </td>
        </tr>
    @empty
        <tr wire:key="no-records-found">
            <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::wire-pagination :paginator="$countries"/>
    </caption>
</x-bs::table>
