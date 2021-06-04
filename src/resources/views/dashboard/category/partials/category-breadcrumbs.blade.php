<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        @isset($category)
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Home') }}</a></li>
        @else
            <li class="breadcrumb-item">{{ __('Home') }}</li>
        @endisset

        @foreach($navbar as $id => $name)
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $id) }}">{{ $name }}</a></li>
        @endforeach

        @isset($category)
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        @endisset
    </ol>
</nav>
