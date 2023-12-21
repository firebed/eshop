<?php

namespace Eshop\Controllers\Dashboard\Invoice;

use Dompdf\Dompdf;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Invoice\Client;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\InvoiceRow;
use Eshop\Requests\Dashboard\Invoice\InvoiceRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;


class InvoiceController extends Controller
{
    use WithNotifications;

    public function index(): Renderable
    {
        $invoices = Invoice::with('client', 'transmission')->latest('published_at')->paginate();

        return $this->view('invoice.index', compact('invoices'));
    }

    public function create(): Renderable
    {
        $clients = Client::orderBy('name')->take(30)->get();

        return $this->view('invoice.create', compact('clients'));
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        $invoice = new Invoice($request->validated());

        DB::transaction(function () use ($request, $invoice) {
            $rows = collect($request->input('rows'));
            $this->updateTotals($invoice, $rows);
            $invoice->number = $invoice->number
                               ?? Invoice::where('type', $request->input('type'))
                                      ->whereYear('published_at', today()->year)
                                      ->where('row', $request->input('row'))
                                      ->max('number') + 1;
            $invoice->save();

            $invoice->rows()->saveMany($rows->transform(fn($r) => new InvoiceRow($r)));
            $this->showSuccessNotification("Αποθηκεύτηκε");
        });

        return redirect()->route('invoices.edit', $invoice);
    }

    public function edit(Invoice $invoice): Renderable
    {
        $clients = Client::orderBy('name')->take(30)->get();

        return $this->view('invoice.edit', compact('invoice', 'clients'));
    }

    public function update(InvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $invoice->fill($request->validated());

            $rows = collect($request->input('rows'));
            $this->updateTotals($invoice, $rows);

            $invoice->save();

            $diff = $invoice->rows->pluck('id')->diff($rows->pluck('id'));
            $invoice->rows()->whereKey($diff)->delete();
            foreach ($rows as $row) {
                $invoice->rows()->updateOrCreate(['id' => $row['id']], $row);
            }
            DB::commit();

            $this->showSuccessNotification("Αποθηκεύτηκε");
        } catch (Throwable) {
            DB::rollBack();
            $this->showErrorNotification("Παρουσιάστηκε κάποιο σφάλμα");
        }

        return back();
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        DB::transaction(static fn() => $invoice->delete());

        return redirect()->route('invoices.index');
    }

    public function searchClients(Request $request): JsonResponse
    {
        $search = $request->input('term');

        $clients = Client
            ::when($search !== '', static fn($q) => $q->where('name', 'LIKE', "%$search%")->orWhere('vat_number', 'LIKE', "$search%"))
            ->orderBy('name')
            ->take(30)
            ->get();

        return response()->json($this->view('invoice.partials.clients', compact('clients'))->render());
    }

    public function print(Invoice $invoice): StreamedResponse
    {
        if (!in_array($invoice->client->country, ['GR', 'CY'])) {
            app()->setLocale('en');
        }
        
        $vats = $invoice->rows
            ->groupBy(fn(InvoiceRow $row) => (string)$row->vat_percent)
            ->map(function ($g, $vat) {
                $total_net_value = round($g->sum(fn($r) => $r['quantity'] * round($r['price'] * (1 - $r['discount']), 4)), 2);
                return [
                    'total_net_value'  => $total_net_value,
                    'total_vat_amount' => round($total_net_value * $vat, 2)
                ];
            });

        $items = $invoice->rows->reject(fn($row) => in_array($row->code, ['SHP', 'PYM']));
        $extra = $invoice->rows->filter(fn($row) => in_array($row->code, ['SHP', 'PYM']));

        $total_extra_value = round($extra->sum(fn($r) => $r->quantity * $r->price), 2);
        $total_value = round($items->sum(fn($r) => $r->quantity * $r->price), 2);
        $total_net_value = $vats->sum('total_net_value') - $total_extra_value;
        $discount_amount = $total_value - $total_net_value;
        $total_vat_amount = $vats->sum('total_vat_amount');


        $qrCode = $invoice->transmission?->qr_url
            ? base64_encode(QrCode::format('svg')->size(100)->generate($invoice->transmission?->qr_url))
            : null;

        $html = $this->view('invoice.print', [
            'invoice'           => $invoice,
            'items'             => $items,
            'units'             => $invoice->rows->groupBy(fn(InvoiceRow $row) => $row->unit->value),
            'vats'              => $vats,
            'total_value'       => $total_value,
            'discount_amount'   => $discount_amount,
            'total_net_value'   => $total_net_value,
            'total_extra_value' => $total_extra_value,
            'total_vat_amount'  => $total_vat_amount,
            'qrCode'            => $qrCode
        ]);

        return response()->stream(function () use ($invoice, $html) {
            $pdf = new Dompdf(['enable_remote' => true]);

            $pdf->loadHtml($html);

            $pdf->render();
            $pdf->stream('invoice-'.$invoice->number.'.pdf', ['Attachment' => 0]);
        });
    }

    private function updateTotals(Invoice $invoice, Collection $rows): void
    {
        $values = $rows->groupBy('vat_percent')
            ->map(fn($g) => round($g->sum(fn($r) => $r['quantity'] * round($r['price'] * (1 - $r['discount']), 4)), 2));

        $invoice->total_net_value = $values->sum();
        $invoice->total_vat_amount = $values->map(fn($v, $k) => round($v * $k, 2))->sum();
        $invoice->total = $invoice->total_net_value + $invoice->total_vat_amount;
    }
}
