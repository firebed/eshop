<div class="d-flex justify-content-between gap-2">
    <h1 class="fw-500 fs-3 mb-0">{{ $category->name ?? __('eshop::category.home') }}</h1>

    <div class="d-flex gap-2">
        <x-bs::dropdown>
            <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __('Actions') }}</x-bs::dropdown.button>
            <x-bs::dropdown.menu button="bulk-actions">
                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#category-move-modal">
                    <em class="fa fa-arrows-alt me-2 text-secondary w-1r"></em>{{ __('Move') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#categories-delete-modal">
                    <em class="far fa-trash-alt me-2 text-secondary w-1r"></em>{{ __('Delete') }}
                </x-bs::dropdown.item>
            </x-bs::dropdown.menu>
        </x-bs::dropdown>

        <a href="{{ route('categories.create', array_filter(['parentId' => $parentId ?? NULL])) }}" class="btn btn-primary">
            <em class="fas fa-plus me-2"></em>
            {{ __('eshop::buttons.add') }}
        </a>
    </div>
</div>

<x-bs::card>
    <div class="table-responsive" x-data="{
        select(checked) {
            $refs.table.querySelectorAll('input[type=checkbox]').forEach(c => c.checked = checked)
        }
    }">
        <x-bs::table hover>
            <thead>
            <tr class="table-light">
                <x-bs::table.heading class="w-1r rounded-top">
                    <x-bs::input.checkbox x-on:change="select($el.checked)" id="select-all"/>
                </x-bs::table.heading>
                <x-bs::table.heading class="w-6r">{{ __("Graphic") }}</x-bs::table.heading>
                <x-bs::table.heading>{{ __("Category") }}</x-bs::table.heading>
                <x-bs::table.heading>{{ __("Translations") }}</x-bs::table.heading>
                <x-bs::table.heading class="rounded-top"/>
            </tr>
            </thead>

            <tbody x-ref="table">
            @forelse($categories as $category)
                <tr wire:key="cat-{{ $category->id }}">
                    <td>
                        <x-bs::input.checkbox :checked="in_array($category->id, old('source_ids', []))" id="category-{{ $category->id }}" value="{{ $category->id }}" class="category"/>
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
                                <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none d-block">{{ $category->name }}</a>
                            @else
                                <em class="fa fa-file-archive text-secondary me-2"></em>
                                <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none d-block">{{ $category->name }} ({{ $category->products_count }})</a>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if ($category->translations_count === 4)
                            <x-bs::badge type="success">{{ $category->translations_count }} / 4</x-bs::badge>
                        @else
                            <x-bs::badge type="warning">{{ $category->translations_count }} / 4</x-bs::badge>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ categoryRoute($category) }}" class="btn btn-sm btn-outline-secondary"><em class="fas fa-globe"></em></a>
                    </td>
                </tr>
            @empty
                <tr wire:key="no-records-found">
                    <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
                </tr>
            @endforelse
            </tbody>

            <caption>
                <x-eshop::wire-pagination :paginator="$categories"/>
            </caption>
        </x-bs::table>
    </div>
</x-bs::card>

@include('eshop::dashboard.category.partials.category-move-modal')
@include('eshop::dashboard.category.partials.categories-delete-modal')