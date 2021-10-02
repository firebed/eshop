<x-bs::table>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>
                <div class="ratio ratio-1x1 w-5r">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $product->trademark }}" class="h-auto mh-100 w-auto mw-100 rounded">
                    @endif
                </div>
            </td>

            <td>{{ $product->trademark }}</td>
            <td class="text-end">{{ format_number($product->pivot->quantity) }} x</td>
            <td class="text-end">{{ format_currency($product->pivot->netValue) }}</td>
        </tr>
    @endforeach
    </tbody>
</x-bs::table>
