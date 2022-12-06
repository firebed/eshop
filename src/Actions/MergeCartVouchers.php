<?php

namespace Eshop\Actions;

use Dompdf\Dompdf;
use Eshop\Models\Cart\Cart;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class MergeCartVouchers
{
    private Fpdi $fpdi;

    public function __construct()
    {
        $this->fpdi = new Fpdi();
    }

    /**
     * @throws Exception
     */
    public function handle(Collection $carts, array $vouchers, bool $two_sided): string
    {
        foreach ($vouchers as $number => $byteArray) {
            $cart = $carts->firstWhere('voucher.number', $number);

            $filename = $this->createOrderDocument($cart);
            $pageCount = $this->fpdi->setSourceFile(Storage::disk('local')->path($filename));

            $voucherImported = false;
            for ($i = 0; $i < $pageCount; $i++) {
                $this->importPage($i + 1);

                if ($two_sided && !$voucherImported) {
                    $this->importVoucher($byteArray);
                    $voucherImported = true;
                }
            }

            if (!$two_sided) {
                $this->importVoucher($byteArray);
            } elseif ($pageCount % 2 === 0) {
                $this->fpdi->AddPage();
            }

            Storage::disk('local')->delete($filename);
        }

        return $this->fpdi->Output('S');
    }

    /**
     * @throws Exception
     */
    private function importPage(int $index): void
    {
        $pageId = $this->fpdi->importPage($index);

        $this->fpdi->AddPage();
        $this->fpdi->useTemplate($pageId);
    }

    /**
     * @throws Exception
     */
    private function importVoucher(string $byteArray): void
    {
        $filename = Str::random(40) . '.pdf';
        Storage::disk('local')->put($filename, base64_decode($byteArray, true));

        $pages = $this->fpdi->setSourceFile(Storage::disk('local')->path($filename));
        for($i = 0; $i < $pages; $i++) {
            $this->importPage($i + 1);
        }

        Storage::disk('local')->delete($filename);
    }

    private function createOrderDocument(Cart $cart): string
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
}