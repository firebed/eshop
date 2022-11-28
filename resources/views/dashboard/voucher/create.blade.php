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
        status: [], 
        total: {{ $carts->count() }},
        success() {
            val = this.total === 0 ? 0 : (this.status.filter(s => s === true).length/this.total*100)
            return Math.round(val+Number.EPSILON)
        }
    }" 
         x-on:status-updated.window="status[$event.detail.cart_id] = $event.detail.status" 
         class="col-12 p-4">
        
        <div class="d-flex mb-3">
            <x-bs::button.primary id="issue-vouchers" :disabled="$carts->isEmpty()">Έκδοση</x-bs::button.primary>
        </div>

        <div class="progress mb-3">
            <div class="progress-bar bg-success" role="progressbar" :style="`width: ${success()}%`">
                <span x-text="`${success()}%`"></span>
            </div>
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

        <livewire:dashboard.voucher.create/>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const vouchersCount = document.querySelectorAll('#vouchers-table tr').length
        
        function issueVoucher(index) {
            if (index > vouchersCount) {
                setTimeout(() => btn.removeAttribute('disabled'), 500)
                return
            }
            
            const tr = document.querySelector('#vouchers-table tr:nth-child(' + index + ')')
            index = index + 1
                        
            tr.dispatchEvent(new CustomEvent('purchase'))

            const filled = tr.querySelector('span[x-text]').childNodes.length > 0
            if (filled) {
                issueVoucher(index)
            } else {
                setTimeout(() => issueVoucher(index), 500)
            }
        }
        
        const btn = document.getElementById('issue-vouchers');
        btn.addEventListener('click', () => {
            btn.setAttribute('disabled', 'disabled')
            issueVoucher(1)
        });
    </script>
@endpush