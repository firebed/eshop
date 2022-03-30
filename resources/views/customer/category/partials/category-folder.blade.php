<div class="d-flex mb-4 align-items-center">
    <h1 class="fs-4 fw-normal">{{ $category->name }}</h1>

    @can('Manage categories')
        <a href="{{ route('categories.edit', $category) }}" class="text-decoration-none ms-auto"><em class="fas fa-edit"></em> {{ __("Edit") }}</a>
    @endcan
</div>

<ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-4 list-unstyled">
    @foreach($children as $child)
        <li class="col">
            <div class="p-3 h-100 bg-white d-flex flex-column gap-3 rounded border">
                <a href="{{ categoryRoute($child) }}" class="ratio ratio-4x3">
                    @if($child->image && $src = $child->image->url('sm'))
                        <img src="{{ $src }}" class="img-top rounded" alt="{{ $child->name }}">
                    @endif
                </a>

                <h3 class="fs-6 fw-500">
                    <a href="{{ categoryRoute($child) }}" class="text-dark text-hover-underline fw-500">{{ $child->name }}</a>
                </h3>

                @if($child->children->isNotEmpty())
                    <p class="mt-auto mb-0">
                        @foreach($child->children as $promoted)
                            <a href="{{ categoryRoute($promoted) }}" class="text-secondary text-hover-underline">{{ $promoted->name }}</a>@unless($loop->last), @endif
                        @endforeach
                    </p>
                @endif
            </div>
        </li>
    @endforeach
</ul>