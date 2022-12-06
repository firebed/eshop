<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OrderTrackingController extends Controller
{
    public function index(): Renderable
    {
        return $this->view('order-tracking.index');
    }

    public function show(Request $request, string $lang, Cart $order): RedirectResponse|Renderable
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('order-tracking.index', $lang);
        }

        $order->load(['products' => fn($q) => $q->with('translations', 'image', 'parent.translations', 'variantOptions.translations')]);
        $products = $order->products;
        
        return $this->view('order-tracking.show', compact('order', 'products'));
    }

    public function searchByVoucher(Request $request, string $lang): RedirectResponse
    {
        $request->validate([
            'voucher' => ['required', 'string', 'exists:vouchers,number'],
        ]);

        $voucher = Voucher::firstWhere('number', $request->input('voucher'));
        
        if ($voucher === null) {
            return redirect()->route('order-tracking.index', $lang);
        }

        return redirect(URL::signedRoute('order-tracking.show', [$lang, $voucher->cart]));
    }

    public function searchById(Request $request, string $lang): RedirectResponse
    {
        $request->validate([
            'id'    => ['required', 'int', 'exists:carts'],
            'email' => ['required', 'email:rfc,dns'],
        ]);

        $cart = Cart::where('id', $request->input('id'))
            ->where('email', $request->input('email'))
            ->first();

        if ($cart === null) {
            return redirect()->route('order-tracking.index', $lang);
        }

        return redirect(URL::signedRoute('order-tracking.show', [$lang, $cart]));
    }
}
