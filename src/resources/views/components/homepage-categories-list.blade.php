<x-bs::list class="h-100 bg-white" flush>
    <x-bs::list.link class="bg-primary text-light disabled"><em class="fa fa-bars me-3"></em> {{ __("All categories") }}</x-bs::list.link>
    @foreach($categories as $category)
        <x-bs::list.link href="{{ route('customer.categories.show', [app()->getLocale(), $category->slug]) }}">{{ $category->name }}</x-bs::list.link>
    @endforeach
    <x-bs::list.link href="#">{{ __("Placeholder") }}</x-bs::list.link>
    <x-bs::list.link href="#">{{ __("Placeholder") }}</x-bs::list.link>
    <x-bs::list.link href="#">{{ __("Placeholder") }}</x-bs::list.link>
    <x-bs::list.link href="#">{{ __("Placeholder") }}</x-bs::list.link>
</x-bs::list>
