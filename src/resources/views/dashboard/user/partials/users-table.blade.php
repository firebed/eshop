<div class="table-responsive">
    <x-bs::table hover>
        <thead>
        <tr class="table-light text-nowrap">
            <x-bs::table.heading class="w-1r rounded-top">
                <x-bs::input.checkbox wire:model="selectAll" id="select-all"/>
            </x-bs::table.heading>
            <td>{{ __('ID') }}</td>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __("Full name") }}</x-bs::table.heading>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('email')" :direction="$sortField === 'email' ? $sortDirection : null">{{ __("Email") }}</x-bs::table.heading>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null" class="rounded-top">{{ __("Created at") }}</x-bs::table.heading>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('last_login_at')" :direction="$sortField === 'last_login_at' ? $sortDirection : null" class="rounded-top">{{ __("Last login") }}</x-bs::table.heading>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('carts_count')" :direction="$sortField === 'carts_count' ? $sortDirection : null" class="rounded-top">{{ __("Orders") }}</x-bs::table.heading>
            <x-bs::table.heading sortable wire:click.prevent="sortBy('carts_sum_total')" :direction="$sortField === 'carts_sum_total' ? $sortDirection : null" class="rounded-top">{{ __("Orders total") }}</x-bs::table.heading>
        </tr>
        </thead>

        <tbody>
        @forelse($users as $user)
            <tr wire:key="cat-{{ $user->id }}">
                <td>
                    <x-bs::input.checkbox wire:model.defer="selected" id="category-{{ $user->id }}" value="{{ $user->id }}"/>
                </td>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('users.show', $user) }}" class="text-decoration-none">{{ $user->full_name }}</a></td>
                <td class="text-secondary">{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>{{ optional($user->last_login_at)->diffForHumans() }}</td>
                <td>{{ format_number($user->carts_count) }}</td>
                <td>{{ format_currency($user->carts_sum_total) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4 fst-italic text-secondary">{{ __('No records found') }}</td>
            </tr>
        @endforelse
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$users"/>
        </caption>
    </x-bs::table>
</div>
