<?php

namespace Eshop\Services\SpeedEx\Http;

use Illuminate\Support\Collection;

class SpeedExCreateVoucherWithPickup extends SpeedExRequest
{
    protected string $action = 'CreateBOLwithOrder';

    public function handle(Collection $carts)
    {
        $list = [];
        foreach ($carts as $cart) {
            $item = [
                '_cust_Flag'          => 0,
                'charge_agreement_id' => '',
                'charge_Customer_id'  => '',
                'Comments_2853_1'     => $cart->id,
                'Items'               => 1,
                'PayCode_Flag'        => 1, // The charge type. 1 => Sender, 2 => Recipient, 3 => Third party
                'RCV_Addr1'           => 'Test customer address',//$cart->shippingAddress->fullstreet,
                'RCV_Country'         => 'GR',
                'RCV_Name'            => 'Test customer name',//$cart->shippingAddress->fullname,
                'RCV_Tel1'            => '0123456789',//$cart->shippingAddress->phone,
                'RCV_Zip_Code'        => $cart->shippingAddress->postcode,
                'Saturday_Delivery'   => 0,
                'Security_Value'      => 0,
                'Snd_agreement_id'    => '003',
                'SND_Customer_Id'     => 'DEMO',
                'Voucher_Weight'      => max(0.5, round($cart->parcel_weight / 1000, 2)),
            ];

            if (filled($cart->details)) {
                $item['Paratiriseis_2853_1'] = $cart->details;
            }

            if ($cart->paymentMethod->isPayOnDelivery()) {
                $item['Pod_Amount_Description'] = 'M';
                $item['Pod_Amount_Cash'] = $cart->total;
            } else {
                $item['Pod_Amount_Cash'] = 0.0;
            }

            $list[] = $item;
        }

        $response = $this->request([
            'inListPod' => $list,
            'tableFlag' => 0
        ]);

        dd($response);

        return $response->outListPod;
    }
}