<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Actions\MergeCartVouchers;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\CourierService;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;
use Eshop\Services\Skroutz\Skroutz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class CartPrintVoucherController extends Controller
{
    public function index(Request $request, CourierService $courierService): Response|string
    {
        $request->validate([
            'ids'       => ['required', 'array', 'exists:carts,id'],
            'joinType'  => ['nullable', 'string', 'in:carts,vouchers'],
            'two_sided' => ['nullable'],
        ]);

        $carts = Cart::query()
            ->whereKey($request->input('ids'))
            ->whereHas('voucher')
            ->with('voucher')
            ->get();

        try {
            $with_carts = $request->input('joinType') === 'carts';
            $joinVouchers = $request->input('joinType') === 'vouchers';

            $vouchers = $courierService->printVouchers($carts->pluck('voucher'), !$with_carts, $joinVouchers);

            if (!$with_carts) {
                return response(base64_decode($vouchers, true), 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'inline; filename=' . time() . '.pdf'
                ]);
            }

            $carts->load('shippingMethod', 'paymentMethod', 'shippingAddress.country');
            $carts->load(['products' => fn($q) => $q->with('translation', 'parent.translation', 'variantOptions.translation')]);

            $byteArray = (new MergeCartVouchers())->handle($carts, $vouchers, $request->boolean('two_sided'));

            return response($byteArray, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename=' . time() . '.pdf'
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function show(Cart $cart): string|RedirectResponse
    {
        if (filled($cart->reference_id) && $cart->channel === 'skroutz') {
            try {
                $order = Skroutz::retrieveOrder($cart->reference_id);
                if (filled($order['courier_voucher'])) {
                    return redirect()->to($order['courier_voucher']);
                }
            } catch (SkroutzException $e) {
                report($e);
            }
        }

        return "<script>window.close();</script>";
    }
}
