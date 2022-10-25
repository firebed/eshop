<?php

namespace Eshop\Services\SpeedEx\Http;

use Eshop\Models\Cart\Cart;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Illuminate\Support\Collection;
use stdClass;

class SpeedExCreateVoucher extends SpeedExRequest
{
    protected string $action = 'CreateBOL';

    /**
     * @param Collection|Cart $carts
     * @return Collection|stdClass
     * @throws SpeedExException
     */
    public function handle(Collection|Cart $carts): Collection|stdClass
    {
        $oneItem = false;
        if ($carts instanceof Cart) {
            $oneItem = true;
            $carts = collect()->add($carts);
        }

        $list = [];
        foreach ($carts as $cart) {
            $list[] = $this->prepareItem($cart);
        }

        try {
            $response = $this->request([
                'inListPod' => $list,
                'tableFlag' => 0
            ]);
        } catch (SpeedExException $ex) {
            if ($ex->getCode() !== 310) {
                throw $ex;
            }

            // 310. Partially failed, this is a soft error, some vouchers were actually created.
            $response = $ex->response;
        }

        $statusList = collect($response->statusList);
        $data = $response->outListPod->BOL ?? null;

        if (!is_array($data)) {
            $data = collect()->add($data);
        }

        $data = $data->map(function ($voucher, $index) use ($statusList, $carts) {
            $cart = $carts->get($index);
            $status = $statusList->get($index);

            return (object)[
                'cart_id'       => $cart->id ?? null,
                'number'        => $voucher->voucher_code ?? null,
                'success'       => filled($voucher->voucher_code ?? null),
                'statusMessage' => $status
            ];
        });

        return $oneItem ? $data->first() : $data;
    }

    private function prepareItem(Cart $cart): array
    {
        $item = [
            '_cust_Flag'        => 0,
            'Comments_2853_1'   => $cart->id,
            'Items'             => 1,
            'PayCode_Flag'      => 1,
            'RCV_Addr1'         => 'Test customer address',//$cart->shippingAddress->fullstreet,
            'RCV_Country'       => 'GR',
            'RCV_Name'          => 'Test customer name',//$cart->shippingAddress->fullname,
            'RCV_Tel1'          => '0123456789',//$cart->shippingAddress->phone,
            'RCV_Zip_Code'      => $cart->shippingAddress->postcode,
            'Saturday_Delivery' => 0,
            'Security_Value'    => 0,
            'Snd_agreement_id'  => '003',
            'SND_Customer_Id'   => 'DEMO',
            'Voucher_Weight'    => max(0.5, round($cart->parcel_weight / 1000, 2)),
        ];

        if (filled($cart->details)) {
            $item['Paratiriseis_2853_1'] = $cart->details;
        }

        if ($cart->paymentMethod->isPayOnDelivery()) {
            $item['Pod_Amount_Description'] = 'M';
            $item['Pod_Amount_Cash'] = number_format($cart->total, 2, '.', '');
        } else {
            $item['Pod_Amount_Cash'] = 0.0;
        }

        return $item;
    }
}