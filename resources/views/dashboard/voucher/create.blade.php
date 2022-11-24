@extends('eshop::dashboard.layouts.master')

@push('header_scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush

@section('header')
    <div class="fs-5 fw-500">Δημιουργία Voucher</div>
@endsection

@section('main')
    <div class="col-12 p-4">

        <div class="d-flex mb-3">
            <x-bs::button.primary id="issue-vouchers">Έκδοση</x-bs::button.primary>
        </div>

        <div class="table-responsive bg-white shadow-sm rounded">
            @include('eshop::dashboard.voucher.partials.orders-voucher-table')
        </div>

        <livewire:dashboard.voucher.create/>
    </div>
@endsection

@push('footer_scripts')
    <script>
        function issueVoucher(index) {
            const tr = document.querySelector('#vouchers-table tr:nth-child(' + index + ')');
            tr.dispatchEvent(new CustomEvent('purchase'))
            
            const len = document.querySelectorAll('#vouchers-table tr').length
            index = index + 1;
            if (index <= len) {
                setTimeout(() => issueVoucher(index), 500)
            }
        }
        
        const btn = document.getElementById('issue-vouchers');
        btn.addEventListener('click', () => issueVoucher(1));
    </script>
@endpush