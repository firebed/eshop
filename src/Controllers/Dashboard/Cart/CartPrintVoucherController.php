<?php

namespace Eshop\Controllers\Dashboard\Cart;

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

class CartPrintVoucherController extends Controller
{
    public function index(Request $request, CourierService $courierService): Response
    {
        $request->validate(['ids' => ['required']]);

        $ids = explode(',', $request->input('ids'));

        $carts = Cart::query()
            ->whereKey($ids)
            ->whereHas('voucher')
            ->with('voucher')
            ->get();

        $pdfs = $courierService->printVouchers($carts->pluck('voucher'));

        $pdf = new Fpdi();

        foreach ($pdfs as $byteArray) {
            $filename = Str::random(40) . '.pdf';
            Storage::disk('local')->put($filename, base64_decode($byteArray, true));

            $pdf->setSourceFile(Storage::disk('local')->path($filename));
            $pageId = $pdf->importPage(1);

            $pdf->AddPage();
            $pdf->useTemplate($pageId);

            Storage::disk('local')->delete($filename);
        }

        //return $pdf->Output('S');
        
        return response($pdf->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename=' . time()
        ]);
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
