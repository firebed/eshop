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
        status: @json($carts->whereNotNull('voucher')->pluck('id')), 
        ids: @json($carts->pluck('id')),
        total: {{ $carts->count() }},
        success() {
            val = this.total === 0 ? 0 : (this.status.length/this.total*100)
            return Math.round(val+Number.EPSILON)
        },
        pushStatus(id) {
            if (!this.status.includes(id)) {
                this.status.push(id)
            }
        },
        
        removeStatus(id) {
            status = this.status.filter((v) => v === id)
        }
    }"
         x-on:status-updated.window="
            id = $event.detail.cart_id
            $event.detail.status ? pushStatus(id) : removeStatus(id)
         "
         class="col-12 p-4">

        <div class="d-flex gap-2 mb-3">
            <x-bs::button.primary id="issue-vouchers" :disabled="$carts->isEmpty()">
                <em class="fa fa-plus me-1"></em> Έκδοση
            </x-bs::button.primary>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#print-vouchers-modal" id="print-vouchers">
                <em class="fa fa-print me-1"></em> Εκτύπωση
            </button>
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

        <form action="{{ route('carts.print-vouchers') }}" target="_blank">
            <div class="modal fade" id="print-vouchers-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Εκτύπωση</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <template x-for="cart_id in status">
                                <input hidden name="ids[]" x-bind:value="cart_id">
                            </template>
                            <x-bs::input.checkbox name="with-cart" id="with-carts">Εκτύπωση των δελτίων παραγγελίας</x-bs::input.checkbox>
                            <x-bs::input.checkbox name="two-sided" id="2-faced">Εκτύπωση διπλής όψης</x-bs::input.checkbox>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                            <button type="submit" class="btn btn-primary">Εκτύπωση</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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