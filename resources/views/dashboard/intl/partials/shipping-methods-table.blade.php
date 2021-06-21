<x-bs::table hover>
    <thead>
    <tr class="table-light text-nowrap">
        <td class="w-1r rounded-top">
            <x-bs::input.checkbox wire:model="selectAll" id="check-all"/>
        </td>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('visible')" :direction="$sortField === 'visible' ? $sortDirection : null" class="w-6r">{{ __("Visible") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('position')" :direction="$sortField === 'position' ? $sortDirection : null">{{ __("Position") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('country')" :direction="$sortField === 'country' ? $sortDirection : null">{{ __("Country") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('method')" :direction="$sortField === 'method' ? $sortDirection : null">{{ __("Method") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('fee')" :direction="$sortField === 'fee' ? $sortDirection : null">{{ __("Fee") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('cart_total')" :direction="$sortField === 'cart_total' ? $sortDirection : null">{{ __("Minimum order") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('weight_limit')" :direction="$sortField === 'weight_limit' ? $sortDirection : null">{{ __("Weight limit") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('excess_weight_fee')" :direction="$sortField === 'excess_weight_fee' ? $sortDirection : null">{{ __("Excess weight fee") }}</x-bs::table.heading>
        <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __("Created at") }}</x-bs::table.heading>
        <x-bs::table.heading class="rounded-top"/>
    </tr>
    </thead>

    <tbody>
    @forelse($shippingMethods as $shippingMethod)
        <tr wire:key="row-{{ $shippingMethod->id }}" @unless($shippingMethod->visible) class="text-gray-500" @endunless>
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" value="{{ $shippingMethod->id }}" id="cb-{{ $shippingMethod->id }}"/>
            </td>
            <td class="align-middle">
                @if($shippingMethod->visible)
                    <em class="fa fa-check-circle text-teal-500"></em>
                @else
                    <em class="fa fa-minus-circle text-warning"></em>
                @endif
            </td>
            <td class="align-middle">{{ $shippingMethod->position }}</td>
            <td class="align-middle">{{ $shippingMethod->country->name }}</td>
            <td class="align-middle text-nowrap">{{ $shippingMethod->shippingMethod->name }}</td>
            <td class="align-middle">{{ format_currency($shippingMethod->fee) }}</td>
            <td class="align-middle">{{ format_currency($shippingMethod->cart_total) }}</td>
            <td class="align-middle">{{ format_weight($shippingMethod->weight_limit) }}</td>
            <td class="align-middle">{{ format_currency($shippingMethod->excess_weight_fee) }}</td>
            <td>{{ __($shippingMethod->created_at->format('d/m/Y')) }}</td>
            <td class="text-end">
                <a href="#" wire:click.prevent="edit({{ $shippingMethod->id }})" class="text-decoration-none">{{ __("Edit") }}</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="11" class="text-center py-4 fst-italic text-secondary">{{ __('No records found') }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::wire-pagination :paginator="$shippingMethods"/>
    </caption>
</x-bs::table>
