@extends('eshop::dashboard.layouts.master')

@section('header', __("Invoices"))

@section('main')
    <div class="col-12 p-4" style="height: calc(100vh - 3.5rem)">

        <form action="{{ route('invoices.update', $invoice) }}" method="post" class="d-flex flex-column gap-4">
            @csrf
            @method('put')
            
            @include('eshop::dashboard.invoice.partials.invoice-header')
            @include('eshop::dashboard.invoice.partials.invoice-clients-search')

            @livewire('dashboard.invoice.rows', ['invoice' => $invoice])
            @livewire('dashboard.invoice.search-products')
        </form>

        <form action="{{ route('invoices.destroy', $invoice) }}" method="post">
            @csrf
            @method('delete')

            <div class="modal fade" id="confirm-invoice-delete" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Διαγραφή</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Διαγραφή τιμολογίου #{{ $invoice->row . ' ' . $invoice->number }} ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                            <button type="submit" class="btn btn-primary">Διαγραφή</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
