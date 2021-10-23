<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\DocumentType;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class Invoice extends Component
{
    use SendsNotifications;
    use TrimStrings;

    public $isInvoice;
    public $cartId;
    public $invoice;
    public $invoiceBilling;
    public $showModal = false;

    protected $listeners = ['cartDocumentUpdated'];

    protected $rules = [
        'invoice.billable_id'   => 'required_if:isInvoice,true|integer',
        'invoice.billable_type' => 'required_if:isInvoice,true|string',
        'invoice.name'          => 'required_if:isInvoice,true|string',
        'invoice.job'           => 'nullable|string',
        'invoice.vat_number'                => 'required_if:isInvoice,true|string',
        'invoice.tax_authority'                => 'nullable|string',

        'invoiceBilling.country_id' => 'required_if:isInvoice,true|integer',
        'invoiceBilling.province'   => 'nullable|string',
        'invoiceBilling.city'       => 'required_if:isInvoice,true|string',
        'invoiceBilling.street'     => 'required_if:isInvoice,true|string',
        'invoiceBilling.street_no'  => 'nullable|string',
        'invoiceBilling.postcode'   => 'required_if:isInvoice,true|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->cartId = $cart->id;
        $this->isInvoice = $cart->isDocumentInvoice();
        $this->invoice = $cart->invoice()->firstOrNew();
        $this->invoiceBilling = $this->invoice->billingAddress()->firstOrNew();
    }

    public function cartDocumentUpdated(string $type): void
    {
        $this->isInvoice = $type === DocumentType::INVOICE;
    }

    public function edit(): void
    {
        $this->skipRender();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isInvoice) {
            $this->invoice->save();
            $this->invoice->billingAddress()->save($this->invoiceBilling);
            $this->invoiceBilling->load('country');
        }

        $this->showSuccessToast('Invoice information saved!');
        $this->showModal = false;
    }

    public function render(): Renderable
    {
        $countries = app('countries');
        $country = $countries->find($this->invoiceBilling->country_id);
        return view('eshop::dashboard.cart.wire.invoice', compact('countries', 'country'));
    }
}
