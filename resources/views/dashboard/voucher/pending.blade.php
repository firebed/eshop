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

        @if($errors->any())
            <div class="bg-white shadow-sm border-start border-4 border-danger p-3 fw-500 d-flex align-items-center gap-3">
                <em class="fa fa-times-circle fa-2x text-danger"></em>
                {{ $errors->first() }}
            </div>
        @endif
        
        @if(session()->has('submitted'))
            <div class="bg-white shadow-sm border-start border-4 border-success p-3 fw-500 d-flex align-items-start gap-3">
                <em class="fa fa-check-circle fa-2x text-success"></em>
                <div>
                    <div>Το κλείσιμο των αποστολών ολοκληρώθηκε με επιτυχία.</div>
                    <div class="text-secondary fw-normal">Αποστολές που έκλεισαν: {{ session('submitted') }}</div>
                </div>
            </div>
        @elseif($vouchers->isNotEmpty())
            <div>
                <button type="button" class="btn btn-primary" data-bs-target="#pending-vouchers-modal" data-bs-toggle="modal">Κλείσιμο αποστολών</button>
            </div>

            <div class="table-responsive bg-white shadow-sm border rounded mt-3">
                <x-bs::table>
                    <thead>
                    <tr>
                        <th>Πελάτης</th>
                        <th>Voucher</th>
                        <th>Courier</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vouchers as $voucher)
                        <tr>
                            <td>
                                <div class="d-grid">
                                    <div class="fw-500">{{ $voucher->cart->shippingAddress->fullname }}</div>
                                    <div class="small text-secondary">{{ $voucher->cart->shippingAddress->city }}, {{ $voucher->cart->shippingAddress->postcode }}</div>
                                </div>
                            </td>
                            <td>{{ $voucher->number }}</td>
                            <td><img src="{{ asset('images/' . $voucher->courier_id->icon()) }}" alt="" class="img-fluid" style="max-height: 20px; max-width: 80px"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </x-bs::table>
            </div>
        @else
            <div class="bg-white shadow-sm border-start border-4 border-warning p-3 fw-500 d-flex align-items-center gap-3">
                <em class="fa fa-exclamation-circle fa-2x text-warning"></em>
                <div>
                    <div>Δεν υπάρχουν αποστολές σε εκκρεμότητα.</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->

    <form action="{{ route('vouchers.submit') }}" method="post">
        @csrf
        <div class="modal fade" id="pending-vouchers-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Κλείσιμο αποστολών</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="bg-white shadow-sm border-warning p-3 border-start border-4 mb-3 d-flex gap-3 align-items-start rounded">
                            <em class="fa fa-exclamation-circle text-warning fa-2x"></em>
                            <div>
                                <div class="fw-500">Προσοχή</div>
                                <ul>
                                    <li>Σε περίπτωση που έχετε εκδώσει κωδικούς από άλλες πλατφόρμες με τον ίδιο κωδικό χρέωσης, το κλείσιμο των αποστολών θα κλείσει επίσης όλες τις υπόλοιπες αποστολές.</li>
                                    <li>Ορισμένες μεταφορικές εταιρείες δεν επιτρέπουν την ακύρωση των αποστολών μετά το κλείσιμο.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                        <button type="submit" class="btn btn-primary">Κλείσιμο αποστολών</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection