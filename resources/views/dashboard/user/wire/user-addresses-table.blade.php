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
            <td>{{ $address->country->name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$addresses"/>
    </caption>
</x-bs::table>
