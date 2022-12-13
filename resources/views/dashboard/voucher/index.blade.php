@extends('eshop::dashboard.layouts.master')

@push('header_scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush

@section('header')
    <div class="fs-5 fw-500">Κωδικοί αποστολής Voucher</div>
@endsection

@section('main')
    <div class="col-12 p-4">
        @include('eshop::dashboard.voucher.partials.voucher-navigation')

        <form method="get" class="d-flex align-items-center justify-content-end gap-1 mb-3">
            <label for="date">Ημερομηνία</label>
            <input class="form-control w-auto" type="date" name="date" id="date" value="{{ $date->format('Y-m-d') }}">
            <button type="submit" class="btn btn-primary">Αναζήτηση</button>
        </form>

        <div class="table-responsive bg-white shadow-sm border rounded">
            <x-bs::table>
                <thead>
                <tr>
                    <th>Παραγγελία</th>
                    <th>Πελάτης</th>
                    <th>Courier</th>
                    <th>Voucher</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($vouchers as $voucher)
                    @php($cart = $carts->find($voucher['reference_1']))
                    
                    <tr>
                        <td>{{ $voucher['reference_1'] }}</td>
                        <td>{{ $cart?->shippingAddress->fullname }}</td>
                        <td class="fw-500">{{ $voucher['courier'] }}</td>
                        <td>{{ $voucher['number'] }}</td>
                        
                        <td class="text-end">
                            {{--                            <button class="btn btn-sm btn-outline-secondary">Ακύρωση</button>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-bs::table>
        </div>
    </div>
@endsection