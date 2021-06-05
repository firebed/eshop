<x-bs::table>
    <thead>
    <tr>
        <td>{{ __('Street') }}</td>
        <td>{{ __('City') }}</td>
        <td>{{ __('Country') }}</td>
    </tr>
    </thead>

    <tbody>
    @foreach($addresses as $address)
        <tr>
            <td>{{ $address->street . ' ' . $address->street_no }}</td>
            <td>{{ $address->city }}, {{ $address->postcode }}</td>
            <td>{{ $address->country->name }}</td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <div class="d-flex justify-content-between align-items-center">
            <div class="small">{{ __('pagination.showing', ['first' => $addresses->firstItem() ?? 0, 'last' => $addresses->lastItem() ?? 0, 'total' => $addresses->total()]) }}</div>
            {{ $addresses->onEachSide(0)->links() }}
        </div>
    </caption>
</x-bs::table>
