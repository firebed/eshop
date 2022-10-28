@extends('eshop::dashboard.layouts.master')

@section('header')
    <a href="{{ route('pickups.index') }}" class="text-dark text-decoration-none">{{ __("Pickup lists") }}</a>
@endsection

@section('main')
    <div class="col-12 p-4 d-grid gap-3">
        <div>
            <h1 class="fs-5">Δημιουργία λιστών παραλαβής</h1>
            <p class="text-secondary">Παρακαλούμε ελέγξτε τις αποστολές σας και πατήστε το κουμπί "Έκδοση" για τη δημιουργία των λιστών παραλαβής.
                Θα δημιουργηθούν διαφορετικές λίστες παραλαβής για κάθε μεταφορική εταιρεία.
                Η διαδικασία αυτή μπορεί να διαρκέσει λίγα δευτερόλεπτα.</p>
        </div>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 row-xl-cols-6 g-3">
            @foreach($totals as $voucher)
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between bg-white p-2 shadow-sm border h-100 rounded">
                        <div class="col-8">
                            <img src="{{ asset('images/' . $voucher['icon']) }}" alt="" class="img-fluid" style="max-height: 30px">
                        </div>
                        
                        <div class="col fw-500 fs-5 mt-auto text-end">{{ $voucher['count'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="">
            <x-bs::button.primary><em class="fa fa-plus"></em> Δημιουργία</x-bs::button.primary>
        </form>

        <div class="table-responsive bg-white rounded-3 border shadow-sm">
            <x-bs::table hover style="table-layout: fixed">
                <thead>
                <tr>
                    <th style="width: 8rem">#Παραγγελία</th>
                    <th>Παραλήπτης</th>
                    <th>Διεύθυνση</th>
                    <th style="width: 10rem">Courier</th>
                    <th style="width: 10rem">Voucher</th>
                    <th style="width: 5rem">Βάρος</th>
                    <th class="text-end" style="width: 8rem">Αντικαταβολή</th>
                </tr>
                </thead>

                <tbody>
                @foreach($vouchers as $voucher)
                    <tr>
                        <td><a href="{{ route('carts.show', $voucher->cart_id) }}">{{ $voucher->cart->id }}</a></td>
                        <td>{{ $voucher->cart->shippingAddress->fullname }}</td>
                        <td>{{ $voucher->cart->shippingAddress->city_or_country }}</td>
                        <td>
                            <img src="{{ asset('images/' . $voucher->shippingMethod->iconSrc()) }}" alt="" class="img-fluid" style="max-height: 24px; max-width: 100px">
                        </td>
                        <td>{{ $voucher->number }}</td>
                        <td class="align-middle">{{ format_weight($voucher->cart->parcel_weight, false) }}</td>
                        <td class="text-end">
                            @if($voucher->cart->paymentMethod->isPayOnDelivery())
                                {{ format_currency($voucher->cart->total) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <caption class="px-2 small">
                    <em class="fa fa-exclamation-circle text-warning"></em> Αν κάποια παραγγελία δεν εμφανίζεται στη λίστα βεβαιωθείτε πως έχετε εκδώσει κωδικό αποστολής και πως δεν το έχετε ακυρώσει.
                </caption>
            </x-bs::table>
        </div>
    </div>
@endsection
