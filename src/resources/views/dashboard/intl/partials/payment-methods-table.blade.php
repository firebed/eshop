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
        <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __("Created at") }}</x-bs::table.heading>
        <x-bs::table.heading class="rounded-top"/>
    </tr>
    </thead>

    <tbody>
    @forelse($paymentMethods as $paymentMethod)
        <tr wire:key="row-{{ $paymentMethod->id }}" @unless($paymentMethod->visible) class="text-gray-500" @endunless>
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" value="{{ $paymentMethod->id }}" id="cb-{{ $paymentMethod->id }}"/>
            </td>
            <td class="align-middle">
                @if($paymentMethod->visible)
                    <em class="fa fa-check-circle text-teal-500"></em>
                @else
                    <em class="fa fa-minus-circle text-warning"></em>
                @endif
            </td>
            <td class="align-middle">{{ $paymentMethod->position }}</td>
            <td class="align-middle">{{ $paymentMethod->country->name }}</td>
            <td class="align-middle text-nowrap">{{ $paymentMethod->paymentMethod->name }}</td>
            <td class="align-middle">{{ format_currency($paymentMethod->fee) }}</td>
            <td class="align-middle">{{ format_currency($paymentMethod->cart_total) }}</td>
            <td>{{ __($paymentMethod->created_at->format('d/m/Y')) }}</td>
            <td class="text-end">
                <a href="#" wire:click.prevent="edit({{ $paymentMethod->id }})" class="text-decoration-none">{{ __("Edit") }}</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center py-4 fst-italic text-secondary">{{ __('No records found') }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$paymentMethods"/>
    </caption>
</x-bs::table>
