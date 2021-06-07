<div class="table-responsive">
    <x-bs::table hover>
        <thead>
        <tr class="table-light">
            <x-bs::table.heading class="w-1r rounded-top">
                <x-bs::input.checkbox wire:model="selectAll" id="select-all"/>
            </x-bs::table.heading>
            <x-bs::table.heading class="w-6r">{{ __("Graphic") }}</x-bs::table.heading>
            <x-bs::table.heading>{{ __("Category") }}</x-bs::table.heading>
            <x-bs::table.heading>{{ __("Products") }}</x-bs::table.heading>
            <x-bs::table.heading>{{ __("Variants") }}</x-bs::table.heading>
            <x-bs::table.heading>{{ __("Translations") }}</x-bs::table.heading>
            <x-bs::table.heading>{{ __("Created at") }}</x-bs::table.heading>
            <x-bs::table.heading class="rounded-top"/>
        </tr>
        </thead>

        <tbody>
        @forelse($categories as $category)
            <tr wire:key="cat-{{ $category->id }}">
                <td>
                    <x-bs::input.checkbox wire:model.defer="selected" id="category-{{ $category->id }}" value="{{ $category->id }}"/>
                </td>
                <td>
                    <div class="ratio ratio-1x1">
                        @isset($category->image)
                            <img class="w-auto h-auto mw-100 mh-100 rounded" src="{{ $category->image->url('sm') }}" alt="{{ $category->name }}">
                        @else
                            <div class="border rounded d-flex align-items-center justify-content-center"><em class="fa fa-image text-gray-500 fa-3x"></em></div>
                        @endisset
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($category->isFolder())
                            <em class="fa fa-folder text-warning me-2"></em>
                        @else
                            <em class="fa fa-file-archive text-secondary me-2"></em>
                        @endif
                        <a href="{{ route('categories.show', $category) }}" class="text-decoration-none d-block">{{ $category->name }}</a>
                    </div>
                </td>
                <td>{{ format_number($category->products_count) }}</td>
                <td>{{ format_number($category->variants_count) }}</td>
                <td>
                    @if ($category->translations_count === 4)
                        <x-bs::badge type="success">{{ $category->translations_count }} / 4</x-bs::badge>
                    @else
                        <x-bs::badge type="warning">{{ $category->translations_count }} / 4</x-bs::badge>
                    @endif
                </td>
                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="#" wire:click.prevent="edit({{ $category->id }})" class="text-decoration-none">{{ __('Edit') }}</a>
                </td>
            </tr>
        @empty
            <tr wire:key="no-records-found">
                <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
            </tr>
        @endforelse
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$categories"/>
        </caption>
    </x-bs::table>
</div>
