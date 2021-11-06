<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Dompdf\Dompdf;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class OrderPrintController extends Controller
{
    public function __invoke(Cart $cart): Response|Application|ResponseFactory
    {
//        if (!$cart->shippingAddress->isInland()) {
//            app()->setLocale('en');
//        }

        $pdf = new Dompdf(['enable_remote' => true]);
        $pdf->loadHtml(view('eshop::customer.order-printer.print', compact('cart')));
        $pdf->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ]
            ])
        );

        $pdf->render();
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename='order-{$cart->id}.pdf'");
    }
}
