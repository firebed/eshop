<div class="d-grid gap-3 card p-3 shadow-sm">
    <div class="d-flex justify-content-between">
        <h1 class="mb-0 fs-5">Έκδοση παραστατικού</h1>
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-alt"><em class="fas fa-save me-1"></em> Αποθήκευση</button>
            @if(request()->routeIs('invoices.edit'))
                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn btn-outline-alt"><em class="fas fa-print me-1"></em> Εκτύπωση</a>

                <a href="#confirm-invoice-delete" data-bs-toggle="modal" class="btn btn-outline-secondary"><em class="far fa-trash-alt"></em></a>
            @endif
        </div>
    </div>

    <div class="row gap-3">
        <div class="col d-grid gap-3">
            <div class="d-flex gap-1">
                <div class="col-7">
                    <label for="invoice-type" class="form-label">Τύπος παραστατικού <span class="text-danger">*</span></label>
                    <select name="type" id="invoice-type" class="form-select">
                        <option disabled selected>Τύπος παραστατικού</option>
                        @foreach(\Eshop\Models\Invoice\InvoiceType::cases() as $type)
                            <option value="{{ $type->value }}" @if(old('type', $invoice->type->value ?? null) == $type->value) selected @endif>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <label for="invoice-row" class="form-label">Σειρά</label>
                    <input type="text" name="row" class="form-control" id="invoice-row" placeholder="Σειρά" value="{{ old('row', $invoice->row ?? eshop('invoice_series', '')) }}">
                </div>

                <div class="col">
                    <label for="invoice-number" class="form-label">Αριθμός</label>
                    <input type="number" name="number" class="form-control input-spin-none" id="invoice-number" placeholder="Αυτόματα" value="{{ old('number', $invoice->number ?? '') }}">
                </div>
            </div>

            <div class="d-flex gap-1">
                <div class="col">
                    <label for="published-at" class="form-label">Ημερομηνία έκδοσης</label>
                    <input type="datetime-local" name="published_at" step="60" class="form-control input-spin-none" id="published-at" value="{{ old('published_at', isset($invoice) ? $invoice->published_at->toDateTimeLocalString() : '') }}">
                </div>

                <div class="col">
                    <label for="relative-document" class="form-label">Σχετικό παραστατικό</label>
                    <input type="text" name="relative_document" class="form-control input-spin-none" id="relative-document" value="{{ old('relative_document', $invoice->relative_document ?? '') }}">
                </div>
            </div>
        </div>

        <div class="col d-grid gap-3">
            <div>
                <input type="hidden" name="client_id" id="client-id" value="{{ old('client_id', $invoice->client_id ?? '') }}">
                <label for="client-title" class="form-label">Πελάτης <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="client-title" placeholder="Πελάτης" disabled value="{{ session('client', isset($invoice) ? $invoice->client->name . " ({$invoice->client->vat_number})" : "") }}">
                    <button data-bs-toggle="modal" data-bs-target="#clients-modal" class="btn btn-outline-secondary" type="button"><em class="fa fa-search"></em></button>
                </div>
            </div>

            <div class="d-flex gap-1">
                <div class="col">
                    <label for="payment-method" class="form-label">Μέθοδος πληρωμής <span class="text-danger">*</span></label>
                    <select name="payment_method" id="payment-method" class="form-select">
                        @foreach(\Eshop\Models\Invoice\PaymentMethod::cases() as $method)
                            <option value="{{ $method->value }}" @if(old('payment_method', $invoice->payment_method->value ?? null) == $method->value) selected @endif>{{ $method->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <label for="transaction-purpose" class="form-label">Σκοπός διακίνησης</label>
                    <input type="text" name="transaction_purpose" class="form-control input-spin-none" id="transaction-purpose" value="{{ old('transaction_purpose', $invoice->transaction_purpose ?? 'Πώληση') }}">
                </div>
            </div>
        </div>
    </div>
</div>