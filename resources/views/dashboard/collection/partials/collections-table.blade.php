<div class="table-responsive"
     x-data="{ checkAll: false }"
     x-effect="$refs.table.querySelectorAll('input[type=checkbox]').forEach(i => i.checked = checkAll)"
>
    <x-bs::table>
        <thead>
        <tr class="table-light text-nowrap">
            <td class="w-1r rounded-top">
                <x-bs::input.checkbox x-model="checkAll" id="check-all"/>
            </td>
            <td>{{ __('eshop::collection.name') }}</td>
            <td class="rounded-top">{{ __('eshop::collection.products') }}</td>
        </tr>
        </thead>

        <tbody x-ref="table">
        @forelse($collections as $collection)
            <tr>
                <td><x-bs::input.checkbox name="ids[]" value="{{ $collection->id }}" class="collection" id="coll-{{ $collection->id }}"/></td>
                <td>
                    <div class="d-grid">
                        <a href="{{ route('collections.edit', $collection) }}">{{ $collection->name }}</a>
                    </div>
                </td>
                <td>{{ $collection->products_count }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
            </tr>
        @endforelse
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$collections"/>
        </caption>
    </x-bs::table>
</div>