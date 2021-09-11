<div class="col">
    <x-bs::card class="h-100 list-group-item-info list-group-item-action">
        <a href="#" wire:click.prevent="loadCategories(null)" class="p-2 d-flex justify-content-between align-items-center text-decoration-none text-dark h-100 rounded">
            {{ __("Categories") }}

            <em class="fas fa-chevron-right"></em>
        </a>
    </x-bs::card>
</div>

@isset($breadcrumbs)
    @foreach($breadcrumbs as $breadcrumb)
        <div class="col">
            <x-bs::card wire:key="breadcrumb-{{ $breadcrumb->id }}" class="h-100 list-group-item-info list-group-item-action">
                <a href="#" wire:click.prevent="{{ $breadcrumb->isFile() ? "loadProducts($breadcrumb->id)" : "loadCategories($breadcrumb->id)" }}" class="p-2 d-flex gap-2 justify-content-between align-items-center text-decoration-none text-dark h-100">
                    {{ $breadcrumb->name }}

                    <em class="fas fa-chevron-right"></em>
                </a>
            </x-bs::card>
        </div>
    @endforeach
@endisset