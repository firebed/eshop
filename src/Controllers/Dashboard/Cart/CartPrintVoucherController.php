<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;
use Eshop\Services\Skroutz\Skroutz;
use Illuminate\Http\RedirectResponse;

class CartPrintVoucherController extends Controller
{
    public function __invoke(Cart $cart): string|RedirectResponse
    {
        if (filled($cart->reference_id) && $cart->channel === 'skroutz') {
            try {
                $order = Skroutz::retrieveOrder($cart->reference_id);
                if (filled($order['courier_voucher'])) {
                    return redirect()->to($order['courier_voucher']);
                }
            } catch (SkroutzException $e) {
                
            }
        }

        return "<script>window.close();</script>";
    }
}
