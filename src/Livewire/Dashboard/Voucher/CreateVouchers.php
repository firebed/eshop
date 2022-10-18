<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Models\Cart\Cart;
use Eshop\Services\Acs\Http\AcsAddressValidation;
use Eshop\Services\Acs\Http\AcsFindAreaByZipcode;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CreateVouchers extends Component
{
    public array $carts            = [];
    public ?int  $cartIndex        = null;
    public bool  $showAddressModal = false;

    public ?string $stationId = null;
    public array   $branches  = [];
    public array   $progress  = [];

    public function mount(array $ids)
    {
        $this->carts = old('carts', []);

        if (blank($this->carts) && filled($ids)) {
            $carts = Cart::whereKey($ids)->with('paymentMethod', 'shippingMethod', 'shippingAddress.country')->get();
            $billingCodes = eshop('acs.billing_codes');

            foreach ($carts as $cart) {
                $this->carts[$cart->id] = [
                    'Pickup_Date'              => today()->format('d/m/Y'),
                    'Sender'                   => config('app.name'),
                    'Recipient_Name'           => $cart->shippingAddress->fullname,
                    'Recipient_Address'        => $cart->shippingAddress->street,
                    'Recipient_Address_Number' => $cart->shippingAddress->street_no,
                    'Recipient_Zipcode'        => $cart->shippingAddress->postcode,
                    'Recipient_Region'         => $cart->shippingAddress->region,
                    'Recipient_Cell_Phone'     => $cart->shippingAddress->phone,
                    'Recipient_Phone'          => null,
                    'Recipient_Floor'          => '',
                    'Recipient_Country'        => $cart->shippingAddress->country->code,
                    'Acs_Station_Destination'  => null,
                    'Billing_Code'             => $billingCodes[$cart->shippingAddress->country->code] ?? null,
                    'Charge_Type'              => 2,
                    'Item_Quantity'            => 1,
                    'Weight'                   => max($cart->parcel_weight / 1000, 0.5),
                ];

                if ($cart->paymentMethod->isPayOnDelivery()) {
                    $this->carts[$cart->id]['Cod_Ammount'] = $cart->total;
                    $this->carts[$cart->id]['Cod_Payment_Way'] = 0;
                    $this->carts[$cart->id]['Acs_Delivery_Products'] = 'COD';
                } else {
                    $this->carts[$cart->id]['Cod_Ammount'] = null;
                    $this->carts[$cart->id]['Cod_Payment_Way'] = null;
                    $this->carts[$cart->id]['Acs_Delivery_Products'] = null;
                }

                $this->carts[$cart->id]['Delivery_Notes'] = $cart->details ?? null;
                $this->carts[$cart->id]['Recipient_Email'] = $cart->email ?? null;
                $this->carts[$cart->id]['Reference_Key1'] = $cart->id;
                $this->carts[$cart->id]['Language'] = null; // Ελληνικά
            }

            $this->progress = array_combine(array_keys($this->carts), array_fill(0, count($this->carts), false));
        }
    }

    public function searchAddress($cartId, AcsFindAreaByZipcode $findArea)
    {
        $this->cartIndex = $cartId;
        $this->branches = $findArea->handle($this->carts[$cartId]['Recipient_Zipcode']);
        $this->showAddressModal = true;
        $this->skipRender();
    }

    public function selectAddress(): void
    {
        $station = $this->branches[$this->stationId];
        $kind = $station['Inaccessible_Area_Kind'];
        $this->carts[$this->cartIndex]['Acs_Station_Destination'] = $station['Station_ID'] . ' ' . $station['Description'] . (filled($kind) ? " ($kind)" : "");
        $this->showAddressModal = false;
        
        $this->progress[$this->cartIndex] = true;
        $this->resetValidation("carts.$this->cartIndex.Acs_Station_Destination");
    }

    public function findStation($cartId, AcsAddressValidation $validation)
    {
        $this->progress[$cartId] = false;

        $cart = $this->carts[$cartId];
        $stations = $validation->handle($cart['Recipient_Address'], $cart['Recipient_Address_Number'], $cart['Recipient_Zipcode']);

        if ($stations->count() === 1) {
            $station = $stations->first();

            $this->carts[$cartId]['Acs_Station_Destination'] = $station['Resolved_Station_ID'] . ' ' . $station['Resolved_Station_Descr'];
            $this->progress[$cartId] = true;
        }

        if (!$this->progress[$cartId]) {
            $this->addError("carts.$cartId.Acs_Station_Destination", 'Error');
        }
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.voucher.wire.create');
    }
}