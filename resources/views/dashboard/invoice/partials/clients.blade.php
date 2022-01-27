<div class="table-responsive px-3 pb-3">
    <table class="table table-hover m-0">
        <thead>
        <tr>
            <td class="fw-500">Επωνυμία</td>
            <td class="fw-500">ΑΦΜ</td>
            <td class="fw-500">Πόλη</td>
        </tr>
        </thead>

        <tbody>
        @foreach($clients as $client)
            <tr>
                <td class="align-middle">{{ $client->name }}</td>
                <td class="align-middle">{{ $client->vat_number }}</td>
                <td class="align-middle">{{ $client->city }} ({{ $client->country }})</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-alt" data-id="{{ $client->id }}" data-country="{{ $client->country }}" data-title="{{ $client->name }} ({{ $client->vat_number }})">Επιλογή</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>