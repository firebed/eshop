<?php

namespace Ecommerce\Controllers\Dashboard\Cart;

use Ecommerce\Models\Cart\Cart;
use Dompdf\Dompdf;
use Ecommerce\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class PrintController extends Controller
{
    public function __invoke(Cart $cart): Response|Application|ResponseFactory
    {
//        dd(storage_path('app/public/images/company-logo.png'));
//        if (!$cart->shippingAddress->isInland()) {
//            app()->setLocale('en');
//        }


        $pdf = new Dompdf(['enable_remote' => TRUE]);
        $pdf->loadHtml(view('com::dashboard.cart.printer.print', compact('cart')));
        $pdf->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer'       => FALSE,
                    'verify_peer_name'  => FALSE,
                ]
            ])
        );

        $pdf->render();
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename='order-{$cart->id}.pdf'");
    }
}
