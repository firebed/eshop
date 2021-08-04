@foreach($categories as $category)
    <div class="col">
        <x-bs::card class="h-100">
            <a x-on:click.prevent="load({{ $category->id }})" href="#" class="d-grid gap-2 p-2 text-decoration-none text-dark list-group-item-action h-100 rounded">
                <div class="ratio ratio-4x3">
                    @if($category->image && $src = $category->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $category->name }}" class="img-middle rounded">
                    @else
                        <em class="fas fa-image fa-4x img-middle text-gray-500"></em>
                    @endif
                </div>
                <div class="text-center">{{ $category->name }}</div>
            </a>
        </x-bs::card>
    </div>
@endforeach
