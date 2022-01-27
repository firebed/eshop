@extends('eshop::dashboard.layouts.master')

@section('header', __("Invoices"))

@section('main')
    <div class="col-12 mx-auto p-4">
        <div class="d-flex gap-1 mb-4">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary"><em class="fas fa-plus me-1"></em>Νέο τιμολόγιο</a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary"><em class="fas fa-users me-2"></em>Πελάτες</a>
            <a href="#confirm-transmission" data-bs-toggle="modal" class="ms-auto btn btn-alt"><em class="far fa-paper-plane me-2"></em>{{ __("Αποστολή στο myDATA") }}</a>
            <a href="#confirm-cancellation" data-bs-toggle="modal" class="ms-2 btn btn-outline-alt"><em class="fas fa-times me-2"></em>Ακύρωση</a>
        </div>

        @if($errors->isNotEmpty())
            <div class="small bg-red-500 p-3 text-white mb-3 rounded shadow-sm d-grid gap-3">
                @foreach($errors->getMessages() as $key => $messages)
                    <ul class="mb-0">
                        <span class="fw-500">Τιμολόγιο {{ $key }}</span>
                        @foreach($messages as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endif

        <form action="{{ route('invoices.send') }}" method="post">
            @csrf
            
            @include('eshop::dashboard.invoice.partials.invoices-table')

            <div class="modal fade" id="confirm-transmission" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Αποστολή στο myDATA</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Αποστολή επιλεγμένων τιμολογίων στο myDATA;
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                            <button type="submit" class="btn btn-alt">Αποστολή</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('invoices.cancel') }}" method="post">
            @csrf

            <div class="modal fade" id="confirm-cancellation" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ακύρωση τιμολογίων</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Αποστολή επιλεγμένων τιμολογίων;
                            <div class="d-none" id="cancel-invoice-ids"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                            <button type="submit" class="btn btn-alt">Ακύρωση</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const cancellationModal = bootstrap.Modal.getInstance(document.getElementById('confirm-cancellation'));
        document.getElementById('confirm-cancellation').addEventListener('show.bs.modal', function(event) {
            const cont = document.getElementById('cancel-invoice-ids');
            cont.innerHTML = "";
            
            document.querySelectorAll("table input[name='ids[]']:checked").forEach(c => {
                const inp = document.createElement('input')
                inp.name = 'ids[]';
                inp.setAttribute('value', c.value);
                cont.append(inp)
            })
        })
    </script>
@endpush
