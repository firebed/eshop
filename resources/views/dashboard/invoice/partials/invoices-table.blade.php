<div class="table-responsive bg-white card shadow-sm">
    <x-bs::table class="table-hover" style="table-layout: fixed">
        <thead>
        <tr>
            <td class="fw-500 text-nowrap w-3r"></td>
            <td class="fw-500 text-nowrap w-10r">myDATA</td>
            <td class="fw-500 text-nowrap w-10r">Ημερομηνία</td>
            <td class="fw-500 text-nowrap w-17r">Τιμολόγιο</td>
            <td class="fw-500 text-nowrap w-7r">Σειρά</td>
            <td class="fw-500 text-nowrap w-7r">Αριθμός</td>
            <td class="fw-500 text-nowrap">Πελάτης</td>
            <td class="fw-500 text-nowrap w-10r text-end">Σύνολο</td>
        </tr>
        </thead>

        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                @if($invoice->type === \Eshop\Models\Invoice\InvoiceType::PRO)
                    <td></td>
                @else
                    <td>
                        <x-bs::input.checkbox name="ids[]" value="{{ $invoice->id }}"/>
                    </td>
                @endif

                @if($invoice->type === \Eshop\Models\Invoice\InvoiceType::PRO)
                    <td></td>
                @else
                    <td class="text-nowrap">
                        <a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">
                            @isset($invoice->transmission)
                                @if($invoice->transmission->isCancelled())
                                    <span class="badge rounded-pill bg-gray-300">{{ $invoice->transmission->cancelled_by_mark }}</span>
                                @else
                                    <span class="badge rounded-pill bg-teal-300">{{ $invoice->transmission->mark }}</span>
                                @endif
                            @else
                                <span class="badge rounded-pill bg-yellow-300">Εκκρεμεί</span>
                            @endisset
                        </a>
                    </td>
                @endif

                <td class="text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ $invoice->published_at->format('d/m/y H:i') }}</a></td>
                <td class="text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ $invoice->type->label() }}</a></td>
                <td class="text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ $invoice->row }}</a></td>
                <td class="text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ $invoice->number }}</a></td>
                <td class="text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ $invoice->client->name }}</a></td>
                <td class="text-end text-nowrap"><a href="{{ route('invoices.edit', $invoice) }}" class="text-decoration-none d-block">{{ format_currency($invoice->total) }}</a></td>
            </tr>
        @endforeach
        </tbody>

        <caption>
            <x-eshop::pagination :paginator="$invoices"/>
        </caption>
    </x-bs::table>
</div>