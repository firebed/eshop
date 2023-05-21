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
            <ul class="bg-white shadow-sm border-start border-4 border-danger list-unstyled p-2 fw-500">
                <li class="text-danger mb-2">Σφάλματα</li>
                {!! implode("", $errors->all("<li>&bullet; :message</li>")) !!}
            </ul>
        @endif

        @if(session()->has('submitted'))
            @if($count = session('submitted'))
                <div class="bg-white shadow-sm border-start border-4 border-success p-3 fw-500 d-flex align-items-start gap-3">
                    <em class="fa fa-check-circle fa-2x text-success"></em>
                    <div>
                        <div>Το κλείσιμο των αποστολών ολοκληρώθηκε με επιτυχία.</div>
                        <div class="text-secondary fw-normal">Αποστολές που έκλεισαν: {{ session('submitted') }}</div>
                    </div>
                </div>
            @else
                <div class="bg-white shadow-sm border-start border-4 border-warning p-3 fw-500 d-flex align-items-start gap-3">
                    <em class="fa fa-info-circle fa-2x text-warning"></em>
                    <div>
                        <div>Δεν βρέθηκαν αποστολές</div>
                        <div class="text-secondary fw-normal">Αποστολές που έκλεισαν: {{ session('submitted') }}</div>
                    </div>
                </div>
            @endif
        @elseif($vouchers->isNotEmpty())
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-target="#pending-vouchers-modal" data-bs-toggle="modal">Κλείσιμο αποστολών</button>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#print-vouchers-modal" id="print-vouchers">
                    <em class="fa fa-print me-1"></em> Εκτύπωση
                </button>
            </div>

            <div class="table-responsive bg-white shadow-sm border rounded mt-3">
                <x-bs::table>
                    <thead>
                    <tr>
                        <th>Παραγγελία</th>
                        <th>Πελάτης</th>
                        <th>Courier</th>
                        <th>Voucher</th>
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

    <form x-data="{ loading: false }" x-on:submit="loading = true" action="{{ route('vouchers.submit') }}" method="post">
        @csrf
        <div class="modal fade" id="pending-vouchers-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Κλείσιμο αποστολών</h5>
                        <button :disabled="loading" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body bg-light position-relative">
                        <div class="bg-white shadow-sm border-warning p-3 border-start border-4 mb-3 d-flex gap-3 align-items-start rounded">
                            <em class="fa fa-exclamation-circle text-warning fa-2x"></em>
                            <div>
                                <div class="fw-500">Προσοχή</div>
                                <ul class="list-unstyled">
                                    <li class="my-2">Σε περίπτωση που έχετε εκδώσει κωδικούς από άλλες πλατφόρμες με τον ίδιο κωδικό χρέωσης, το κλείσιμο των αποστολών θα κλείσει επίσης όλες τις υπόλοιπες αποστολές.</li>
                                    <li>Ορισμένες μεταφορικές εταιρείες δεν επιτρέπουν την ακύρωση των αποστολών μετά το κλείσιμο. Στην περίπτωση αυτή θα πρέπει να επικοινωνήσετε με τη μεταφορική εταιρεία.</li>
                                </ul>
                            </div>
                        </div>

                        <div x-show="loading" x-cloak class="text-center py-1"><em class="fa fa-spinner fa-spin"></em> Παρακαλώ περιμένετε...</div>
                    </div>
                    <div class="modal-footer">
                        <button :disabled="loading" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                        <button :disabled="loading" type="submit" class="btn btn-primary">Κλείσιμο αποστολών</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('carts.print-vouchers') }}" method="post" target="_blank">
        @csrf

        @include('eshop::dashboard.voucher.partials.print-vouchers-modal', ['cartIds' => $vouchers->pluck('reference_1')])
    </form>
@endsection
