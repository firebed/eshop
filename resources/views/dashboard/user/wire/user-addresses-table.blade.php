<div class="d-grid gap-2">
    <div class="table-responsive">
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
    </div>

    <div>
        <button wire:click.prevent="deleteAll()" wire:loading.attr="disabled" class="btn btn-sm btn-warning">Διαγραφή όλων</button>
    </div>
</div>