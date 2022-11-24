<x-bs::table hover style="table-layout: fixed">
    <thead>
    <tr>
        <th style="width: 6rem">#</th>
        <th style="width: 10rem">Voucher</th>
        <th>Παραλήπτης</th>
        <th>Διεύθυνση</th>
        <th style="width: 10rem">Courier</th>
        <th class="text-end" style="width: 5rem">Βάρος</th>
        <th class="text-end" style="width: 8rem">Αντικαταβολή</th>
        <th class="text-end" style="width: 4rem"></th>
    </tr>
    </thead>
    <tbody id="vouchers-table">
    @foreach($carts as $cart)
        <livewire:dashboard.voucher.table-row :cart="$cart"/>
    @endforeach
    </tbody>
</x-bs::table>