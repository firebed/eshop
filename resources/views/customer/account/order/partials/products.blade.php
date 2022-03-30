<x-bs::table>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td style="width: 7rem">
                <div class="ratio ratio-1x1 w-5r">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $product->trademark }}" class="h-auto mh-100 w-auto mw-100 rounded">
                    @endif
                </div>
            </td>

            <td>
                @if($product->isVariant())
                    <div class="vstack">
                        <div class="fw-500">{{ $product->parent->name }}</div>
                        <small class="text-secondary">{{ $product->option_values }}</small>
                    </div>
                @else
                    <div class="fw-500">{{ $product->trademark }}</div>
                @endif
            </td>
            <td class="text-end">{{ format_number($product->pivot->quantity) }}&nbsp;x</td>
            <td class="text-end">{{ format_currency($product->pivot->netValue) }}</td>
        </tr>
    @endforeach
    </tbody>
</x-bs::table>
