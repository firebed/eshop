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
    <div x-data="vouchers(@json($carts->pluck('id')))" class="col-12 p-4">
        <div class="d-flex gap-2 mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issue-vouchers-modal" @if($carts->isEmpty()) disabled="disabled" @endif>
                <em class="fa fa-plus me-1"></em> Έκδοση
            </button>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#print-vouchers-modal" id="print-vouchers">
                <em class="fa fa-print me-1"></em> Εκτύπωση
            </button>
        </div>

        <div class="table-responsive bg-white shadow-sm rounded border">
            <x-bs::table hover style="table-layout: fixed">
                <thead>
                <tr>
                    <th style="width: 6rem">#</th>
                    <th style="width: 10rem">Voucher</th>
                    <th>Παραλήπτης</th>
                    <th style="width: 7rem">Τεμάχια</th>
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

        <form action="{{ route('carts.print-vouchers') }}" method="post" target="_blank">
            @csrf
            
            @include('eshop::dashboard.voucher.partials.print-vouchers-modal', ['cartIds' => $carts->pluck('id')])
        </form>

        <livewire:dashboard.voucher.create/>
    </div>
@endsection

@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('vouchers', () => ({
                loading: false,
                finished: false,
                rows: [],
                successCount: 0,
                failedCount: 0,
                errors: [],

                setup() {
                    this.rows = [...document.querySelectorAll('#vouchers-table tr')].filter(tr => tr.dataset.voucher.length === 0);
                },

                dispatch() {
                    this.setup();

                    if (this.loading || this.rows.length === 0) {
                        return;
                    }

                    this.finished = false;
                    this.successCount = 0;
                    this.failedCount = 0;
                    const promises = [];
                    const events = [];

                    this.rows.forEach(tr => {
                        const detail = {};

                        promises.push(
                            new Promise((resolve, reject) => {
                                detail.resolve = resolve
                                detail.reject = reject
                            })
                                .then(() => this.successCount++)
                                .catch(() => this.failedCount++)
                        );

                        events.push(() => tr.dispatchEvent(new CustomEvent('create-voucher', {detail})));
                    });

                    this.loading = true;
                    Promise.allSettled(promises).then(() => {
                        this.loading = false;
                        this.finished = true;
                    });
                    events.forEach((event, i) => setTimeout(() => event(), i * 350));
                },

                successRate() {
                    const total = this.rows.length;

                    return total === 0 ? 0 : Math.round((this.successCount / total) * 100) + '%';
                }
            }));
        });
    </script>
@endpush