<?php


namespace Eshop\Livewire\Customer\Checkout;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Cart\DocumentType;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditCheckoutDetails extends Component
{
    use ControlsOrder;

    public string  $email   = "";
    public string  $details = "";
    public Invoice $invoice;
    public Address $shipping;
    public bool    $invoicing;
    public Address $invoiceAddress;
    public int     $selectedShipping;

    public function rules(): array
    {
        return [
            // Shipping address
            'shipping.first_name'       => ['required_if:selectedShipping,0', 'string'],
            'shipping.last_name'        => ['required_if:selectedShipping,0', 'string'],
            'shipping.phone'            => ['required_if:selectedShipping,0', 'string'],
            'shipping.country_id'       => ['required_if:selectedShipping,0', 'integer', 'exists:countries,id'],
            'shipping.province'         => ['required_if:selectedShipping,0', 'string'],
            'shipping.city'             => ['required_if:selectedShipping,0', 'string'],
            'shipping.street'           => ['required_if:selectedShipping,0', 'string'],
            'shipping.street_no'        => ['nullable', 'string'],
            'shipping.postcode'         => ['required_if:selectedShipping,0', 'string'],

            // Invoicing
            'invoice.name'              => ['required_if:invoicing,true', 'string'],
            'invoice.job'               => ['required_if:invoicing,true', 'string'],
            'invoice.vat_number'        => ['required_if:invoicing,true', 'string', 'max:20'],
            'invoice.tax_authority'     => ['required_if:invoicing,true', 'string'],
            'invoiceAddress.phone'      => ['required_if:invoicing,true', 'string'],
            'invoiceAddress.country_id' => ['required_if:invoicing,true', 'integer', 'exists:countries,id'],
            'invoiceAddress.province'   => ['required_if:invoicing,true', 'string'],
            'invoiceAddress.street'     => ['required_if:invoicing,true', 'string'],
            'invoiceAddress.street_no'  => ['nullable', 'string'],
            'invoiceAddress.city'       => ['required_if:invoicing,true', 'string'],
            'invoiceAddress.postcode'   => ['required_if:invoicing,true', 'string'],

            // Customer notes
            'email'                     => [Auth::check() ? 'prohibited' : 'required', 'email:rfc,dns'],
            'details'                   => ['nullable', 'string', 'max:255']
        ];
    }

    public function mount(Order $order): void
    {
        if ($order->isNotEmpty() && DB::transaction(fn() => $this->softRefreshOrder($order))) {
            session()->flash('products-values-changed');
        }

        $this->email = Auth::guest() ? $order->email : "";
        $this->details = $order->details ?? "";
        $this->invoicing = $order->document_type === DocumentType::INVOICE;

        $address = $order->shippingAddress()->firstOrNew();
        if (!empty($address->related_id)) {
            $this->shipping = $order->shippingAddress()->make();
            $this->selectedShipping = $address->related_id;
        } else {
            $this->shipping = $address;
            $this->selectedShipping = 0;
        }

        $this->invoice = $order->invoice()->firstOrNew();
        $this->invoiceAddress = $this->invoice->billingAddress()->firstOrNew();
    }

    /**
     * @throws ValidationException
     */
    public function updatedShippingCountryId(): void
    {
        $this->validateOnly('shipping.country_id');

        $order = app(Order::class);
        $order->shippingAddress()->updateOrCreate([], $this->shipping->getAttributes() + ['cluster' => 'shipping']);
        $this->refreshOrder($order);
    }

    public function updatedSelectedShipping(): void
    {
        $order = app(Order::class);

        if (empty($this->selectedShipping)) {
            $clone = $this->shipping;
            $clone->related_id = NULL;
        } else {
            $clone = Address::find($this->selectedShipping)->replicate(['addressable_type', 'addressable_id']);
            $clone->related_id = $this->selectedShipping;
        }
        $clone->cluster = 'shipping';

        $order->shippingAddress()->updateOrCreate([], $clone->getAttributes());
        $this->refreshOrder($order);
    }

    public function save(Order $order): void
    {
        $this->validate();

        $order->email = Auth::check() ? auth()->user()->email : $this->email;
        $order->details = blank($this->details) ? NULL : trim($this->details);
        $order->ip = request()->ip();
        $order->document_type = $this->invoicing ? DocumentType::INVOICE : DocumentType::RECEIPT;
        $order->save();

        $order->shippingAddress()->update($this->shipping->getAttributes());

        $invoiceFilled = collect(array_merge($this->invoice->getAttributes(), $this->invoiceAddress->getAttributes()))->filter()->isNotEmpty();
        if ($invoiceFilled) {
            $invoice = $order->invoice()->updateOrCreate([], $this->invoice->getAttributes());
            $invoice->billingAddress()->updateOrCreate([], $this->invoiceAddress->getAttributes());
        }

        $this->redirectRoute('checkout.payment.edit', app()->getLocale());
    }

    public function render(Order $order): Renderable
    {
        $country = $order->shippingAddress->country ?? NULL;

        $order->products->load('parent', 'options');
        $order->products->merge($order->products->pluck('parent')->filter())->load('translation');

        return view('eshop::customer.checkout.details.wire.edit', [
            'order'           => $order,
            'addresses'       => Auth::check() ? user()->addresses : collect(),
            'countries'       => Country::visible()->orderBy('name')->get(),
            'shippingMethods' => $country ? $country->filterShippingOptions($order->products_value) : collect()
        ]);
    }
}
