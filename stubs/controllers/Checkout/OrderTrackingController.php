<?php

namespace App\Http\Controllers\Checkout;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OrderTrackingController extends Controller
{
    public function index(): Renderable
    {
        return view('order-tracking.index');
    }

    public function show(Request $request, string $lang, Cart $order): RedirectResponse|Renderable
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('order-tracking.index', $lang);
        }

        $order->load(['products' => fn($q) => $q->with('translation', 'image')]);
        $products = $order->products;

        return view('order-tracking.show', compact('order', 'products'));
    }

    public function searchByVoucher(Request $request, string $lang): RedirectResponse
    {
        $request->validate([
            'voucher' => ['required', 'string', 'exists:carts,voucher'],
        ]);

        $cart = Cart::firstWhere('voucher', $request->input('voucher'));

        if ($cart === null) {
            return redirect()->route('order-tracking.index', $lang);
        }

        return redirect(URL::signedRoute('order-tracking.show', [$lang, $cart]));
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
