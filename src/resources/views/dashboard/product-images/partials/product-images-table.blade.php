<x-bs::table>
    <thead>
    <tr class="table-light">
        <td class="w-7r rounded-top">{{ __("Image") }}</td>
        <td>{{ __("Dimensions") }}</td>
        <td>{{ __("Size") }}</td>
        <td class="text-end rounded-top"></td>
    </tr>
    </thead>

    <tbody>
    @foreach($images as $image)
        <tr wire:key="image-{{ $image->id }}">
            <td>
                <div class="ratio ratio-1x1">
                    <img src="{{ $image->url('sm') }}" alt="" class="img-middle rounded">
                </div>
            </td>

            <td>{{ $image->dimensions }}</td>

            <td>{{ format_bytes($image->conversions_total_size) }}</td>

            <td class="text-end">
                <x-bs::button.haze wire:click.prevent="delete({{ $image->id }})" wire:loading.attr="disabled" wire:target="delete({{ $image->id }})">
                    <em class="far fa-trash-alt"></em>
                </x-bs::button.haze>
            </td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <x-pagination :paginator="$images"/>
    </caption>
</x-bs::table>
