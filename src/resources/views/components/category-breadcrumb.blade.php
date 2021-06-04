<div class="container-fluid pt-3">
    <div class="container">
        <nav aria-label="breadcrumb" class="small">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale()) }}" class="text-decoration-none text-secondary">{{ __("Home") }}</a></li>
                @foreach($categories as $category)
                    <li class="breadcrumb-item"><a href="{{ route('customer.categories.show', [app()->getLocale(), $category->slug]) }}" class="text-decoration-none text-secondary">{{ $category->name }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('customer.categories.show', [app()->getLocale(), $leaf->slug]) }}" class="text-decoration-none text-secondary">{{ $leaf->name }}</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
