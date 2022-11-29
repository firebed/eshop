<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Dompdf\Dompdf;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\CourierService;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;
use Eshop\Services\Skroutz\Skroutz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use Throwable;

class CartPrintVoucherController extends Controller
{
    public function index(Request $request, CourierService $courierService): Response|string
    {
        $request->validate([
            'ids'        => ['required', 'array', 'exists:carts,id'],
            'two_sided'  => ['nullable'],
            'with_carts' => ['nullable']
        ]);

        $carts = Cart::query()
            ->whereKey($request->input('ids'))
            ->whereHas('voucher')
            ->with('voucher', 'shippingMethod', 'paymentMethod', 'shippingAddress.country')
            ->with(['products' => fn($q) => $q->with('translation', 'parent.translation', 'variantOptions.translation')])
            ->get();

        try {
            $with_carts = $request->boolean('with_carts');
            $vouchers = $courierService->printVouchers($carts->pluck('voucher'), $with_carts);
            
            if (!$with_carts) {
                return response(base64_decode($vouchers, true), 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'inline; filename=' . time()
                ]);

            }
        } catch (Throwable) {
            return "<script>window.close();</script>";
        }

        $pdf = new Fpdi();
        foreach ($vouchers as $number => $byteArray) {
            $cart = $carts->firstWhere('voucher.number', $number);
            $fn = $this->cartPdf($cart);

            $pageCount = $pdf->setSourceFile(Storage::disk('local')->path($fn));
            $pageId = $pdf->importPage(1);

            $pdf->AddPage();
            $pdf->useTemplate($pageId);

            $filename = Str::random(40) . '.pdf';
            Storage::disk('local')->put($filename, base64_decode($byteArray, true));

            $pdf->setSourceFile(Storage::disk('local')->path($filename));
            $pageId = $pdf->importPage(1);

            $pdf->AddPage();
            $pdf->useTemplate($pageId);

            Storage::disk('local')->delete([$fn, $filename]);

            for ($i = 1; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $pageId = $pdf->importPage($i);
                $pdf->useTemplate($pageId);
            }

            if ($pageCount % 2 === 0) {
                $pdf->AddPage();
            }
        }

        return response($pdf->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename=' . time()
        ]);
    }

    private function cartPdf(Cart $cart)
    {
        $pdf = new Dompdf(['enable_remote' => true]);
        $pdf->loadHtml(view('eshop::customer.order-printer.print', [
            'cart'     => $cart,
            'products' => $cart->products
        ]));
        $pdf->render();

        $fn = Str::random(40) . '.pdf';
        Storage::disk('local')->put($fn, $pdf->output());

        return $fn;
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

            }
        }

        return "<script>window.close();</script>";
    }
}
