<?php

namespace Eshop\Controllers\Dashboard\Invoice;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Invoice\Traits\TransformsInvoice;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\InvoiceTransmission;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\InvoicesDoc;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoiceTransmissionController extends Controller
{
    use TransformsInvoice;
    
    public function __construct()
    {
        self::initMyData();
    }

    private static function initMyData(): void
    {
        MyDataRequest::setEnvironment(config('mydata.env'));
        MyDataRequest::setCredentials(config('mydata.user_id'), config('mydata.subscription_key'));
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['required', 'integer']
        ]);

        $invoices = Invoice::with('client')->findMany($request->input('ids'));

        $invoicesDoc = new InvoicesDoc();
        foreach ($invoices as $invoice) {
            $invoicesDoc->addInvoice($this->transform($invoice));
        }

        $errors = collect();

        try {
            $sendInvoices = new SendInvoices();
            $response = $sendInvoices->handle($invoicesDoc);

            foreach ($response->getResponseTypes() as $responseType) {
                $invoice = $invoices->get($responseType->getIndex() - 1);

                if ($responseType->getStatusCode() === 'Success') {
                    $invoice->transmissions()->save(new InvoiceTransmission([
                        'uid'               => $responseType->getInvoiceUid(),
                        'mark'              => $responseType->getInvoiceMark(),
                        'cancelled_by_mark' => $responseType->getCancellationMark(),
                    ]));
                } else {
                    $invoiceErrors = [];
                    foreach ($responseType->getErrors()->getErrorsTypes() as $error) {
                        $invoiceErrors[] = $error->getCode() . ': ' . $error->getMessage();
                    }

                    $errors->put($invoice->row . ' - ' . $invoice->number, $invoiceErrors);
                }
            }
        } catch (GuzzleException) {
            $errors->put('connection', "Αποτυχία σύνδεσης");
        }

        if (filled($errors)) {
            return back()->withErrors($errors->all());
        }

        return back();
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['required', 'integer']
        ]);

        $invoices = Invoice::with('transmission')->findMany($request->input('ids'));

        $errors = collect();
        
        try {
            $cancelInvoice = new CancelInvoice();
            foreach($invoices as $invoice) {
                $response = $cancelInvoice->handle($invoice->transmission->mark);
                foreach ($response->getResponseTypes() as $responseType) {
                    if ($responseType->getStatusCode() === 'Success') {
                        $invoice->transmission->update([
                            'cancelled_by_mark' => $responseType->getCancellationMark()
                        ]);
                    } else {
                        $invoiceErrors = [];
                        foreach ($responseType->getErrors()->getErrorsTypes() as $error) {
                            $invoiceErrors[] = $error->getCode() . ': ' . $error->getMessage();
                        }

                        $errors->put($invoice->row . ' - ' . $invoice->number, $invoiceErrors);
                    }                    
                }
            }
        } catch (GuzzleException) {
            $errors->put('connection', "Αποτυχία σύνδεσης");
        }

        if (filled($errors)) {
            return back()->withErrors($errors->all());
        }
        
        return back();
    }
}