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
    <div x-data="{ 
        successful: @json($carts->whereNotNull('voucher')->pluck('id')), 
        failed: [], 
        ids: @json($carts->pluck('id')),
        total: {{ $carts->count() }},
        successRate() {
            val = this.total === 0 ? 0 : (this.successful.length/this.total*100)
            return Math.round(val+Number.EPSILON)
        },
        
        success(id) {
            this.failed = this.failed.filter((v) => v === id)
        
            if (!this.successful.includes(id)) {
                this.successful.push(id)
            }
        },    
        
        failed(id) {
            this.successful = this.successful.filter((v) => v === id)
            
            if (!this.failed.includes(id)) {
                this.failed.push(id)
            }
        },
        
        remove(id) {
            this.successful = this.successful.filter((v) => v === id)
            this.failed = this.failed.filter((v) => v === id)
        },    
    }"
         x-on:status-updated.window="
            id = $event.detail.cart_id
            $event.detail.status ? success(id) : failed(id)
         "
         class="col-12 p-4">

        <div class="d-flex gap-2 mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issue-vouchers-modal" @if($carts->isEmpty()) disabled="disabled" @endif>
                <em class="fa fa-plus me-1"></em> Έκδοση
            </button>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#print-vouchers-modal" id="print-vouchers">
                <em class="fa fa-print me-1"></em> Εκτύπωση
            </button>
        </div>

        <div class="table-responsive bg-white shadow-sm rounded">
            <x-bs::table hover style="table-layout: fixed">
                <thead>
                <tr>
                    <th style="width: 6rem">#</th>
                    <th style="width: 10rem">Voucher</th>
                    <th>Παραλήπτης</th>
                    <th style="width: 10rem">Courier</th>
                    <th class="text-end" style="width: 5rem">Βάρος</th>
                    <th class="text-end" style="width: 8rem">Αντικαταβολή</th>
                    <th class="text-end" style="width: 4rem"></th>
                </tr>
                </thead>
                <tbody id="vouchers-table">
                @forelse($carts as $cart)
                    <livewire:dashboard.voucher.table-row :cart="$cart" wire:key="row-{{ $cart->id }}"/>
                @empty
                    <tr>
                        <td colspan="7" class="py-5 small text-secondary text-center">
                            <em class="fa fa-exclamation-circle"></em>
                            Δεν υπάρχουν παραγγελίες για τις οποίες μπορούν να δημιουργηθούν κωδικούς αποστολής (voucher).
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </x-bs::table>
        </div>

        @include('eshop::dashboard.voucher.partials.issue-vouchers-modal')
        @include('eshop::dashboard.voucher.partials.print-vouchers-modal')

        <livewire:dashboard.voucher.create/>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const vouchersCount = document.querySelectorAll('#vouchers-table tr').length
        let promises = []
        let events = []
        const btn = document.getElementById('issue-vouchers');

        btn.addEventListener('click', () => {
            document.querySelectorAll('#vouchers-table tr').forEach(() => {
                promises.push(new Promise((resolve, reject) => {
                    events.push({resolve, reject})
                }))
            });

            Promise.allSettled(promises).then(() => {
                window.dispatchEvent(new CustomEvent('create-vouchers-finished'))
            })

            window.dispatchEvent(new CustomEvent('create-vouchers-started'))

            for (let i = 0; i < promises.length; i++) {
                setTimeout(() => {
                    const index = i + 1;
                    const event = events[i]
                    const tr = document.querySelector('#vouchers-table tr:nth-child(' + index + ')')
                    tr.dispatchEvent(new CustomEvent('purchase', {
                        detail: {
                            resolve: event.resolve,
                            reject: event.reject,
                        }
                    }))
                }, i * 500)
            }
        });
    </script>
@endpush