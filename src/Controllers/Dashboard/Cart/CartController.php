<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Services\CourierCenter\Http\CourierCenterGetStations;
use Eshop\Services\SpeedEx\Enums\SpeedExPaperType;
use Eshop\Services\SpeedEx\Http\SpeedExCreateVoucher;
use Eshop\Services\SpeedEx\Http\SpeedExGetDepositedConsignmentsByDate;
use Eshop\Services\SpeedEx\Http\SpeedExGetTraceByVoucher;
use Eshop\Services\SpeedEx\Http\SpeedExGetVoucherPdf;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Cart::class, 'cart');
    }

    public function index(): Renderable
    {
        return $this->view('cart.index');
    }

    public function show(Cart $cart)
    {
        //dd((new SpeedExCreateVoucher())->handle(collect()->add($cart)));
        //$pdf = ((new SpeedExGetVoucherPdf())->handle('700017925805'));s
        //dump($pdf);
        $pdf = ((new SpeedExGetVoucherPdf())->handle(['700017925802', '700017925803', '700017925804', '700017925805'], SpeedExPaperType::ENVELOPE));
        //dd($pdf);
        //$pdf2->dd();
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cart.pdf"'
        ]);
        dd($bytes);
        
        
        if ($cart->isSubmitted()) {
            if (!$cart->isViewed()) {
                $cart->viewed_at = now();
                $cart->save();
                
                CartEvent::orderViewed($cart->id);
            }

            $assignment = $cart->operators()->firstWhere('user_id', auth()->id());
            $assignment?->pivot?->update(['viewed_at' => now()]);
        }
        
        return $this->view('cart.show', compact('cart'));
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();
        return redirect()->route('eshop::dashboard.carts.index');
    }
}
