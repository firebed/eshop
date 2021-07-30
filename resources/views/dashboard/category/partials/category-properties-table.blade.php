<x-bs::card>
    <x-bs::table>
        <thead>
        <tr class="table-light">
            <td>{{ __('Name') }}</td>
            <td class="text-center">{{ __('eshop::category.property_type') }}</td>
            <td class="text-center">{{ __('eshop::category.visible') }}</td>
            <td class="text-center">{{ __('Translations') }}</td>
            <td class="rounded-top text-end"></td>
        </tr>
        </thead>

        <tbody>
        @forelse($properties as $property)
            <tr>
                <td class="align-middle"><a href="{{ route('categories.properties.edit', $property) }}" class="d-block">{{ $property->name }}</a></td>

                <td class="align-middle text-center">{{ __("eshop::category.property_$property->type") }}
                </td>

                <td class="align-middle text-center">
                    @if($property->visible)
                        <em class="fa fa-check-circle text-teal-500"></em>
                    @else
                        <em class="fa fa-minus-circle text-warning"></em>
                    @endif
                </td>

                <td class="align-middle text-center">
                    @if($property->translations_count === 2)
                        <x-bs::badge type="success">{{ $property->translations_count }}</x-bs::badge>
                    @else
                        <x-bs::badge type="warning">{{ $property->translations_count }}/2</x-bs::badge>
                    @endif
                </td>

                <td class="text-end align-middle text-nowrap">
                    <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.properties.moveUp', $property) }}" method="post">
                        @csrf
                        @method('put')

                        <x-bs::button.link :disabled="$loop->first" size="sm" type="submit">
                            <em class="fa fa-chevron-up"></em>
                        </x-bs::button.link>
                    </form>

                    <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="{{ route('categories.properties.moveDown', $property) }}" method="post">
                        @csrf
                        @method('put')

                        <x-bs::button.link :disabled="$loop->last" size="sm" type="submit">
                            <em class="fa fa-chevron-down"></em>
                        </x-bs::button.link>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="d-grid gap-1">
                        <div class="text-secondary fst-italic">{{ __('eshop::category.empty_table') }}</div>
                        <a href="{{ route('categories.properties.create', $category) }}" class="text-decoration-none">
                            <em class="fas fa-plus me-2"></em>{{ __('eshop::category.create_property') }}
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$properties"/>
        </caption>
    </x-bs::table>
</x-bs::card>