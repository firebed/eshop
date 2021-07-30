<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        @isset($category)
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('eshop::category.home') }}</a></li>
        @else
            <li class="breadcrumb-item">{{ __('Home') }}</li>
        @endisset

        @isset($breadcrumbs)
            @foreach($breadcrumbs as $id => $name)
                <li class="breadcrumb-item"><a href="{{ route('categories.edit', $id) }}">{{ $name }}</a></li>
            @endforeach
        @endisset

        @isset($category)
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        @endisset
    </ol>
</nav>
