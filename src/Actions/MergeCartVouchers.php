<?php

namespace Eshop\Actions;

use Dompdf\Dompdf;
use Eshop\Models\Cart\Cart;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReference;

class MergeCartVouchers
{
    private Fpdi $fpdi;

    public function __construct()
    {
        CrossReference::$trailerSearchLength = 22000;
        $this->fpdi = new Fpdi();
    }

    /**
     * @throws Exception
     */
    public function handle(Collection $carts, array $vouchers, bool $two_sided): string
    {
        foreach ($vouchers as $number => $byteArray) {
            $cart = $carts->firstWhere('voucher.number', $number);

            // Create the order's pdf and use it as the source file
            $orderPdf = $this->createOrderDocument($cart);
            $disk = Storage::disk('local');
            $pageCount = $this->fpdi->setSourceFile($disk->path($orderPdf));

            for ($i = 1; $i <= $pageCount; $i++) {
                $this->importPage($i);

                // If two-sided printing is enabled, and we are on the first page 
                if ($two_sided && $i === 1) {
                    // Import the voucher pdf
                    $this->importVoucher($byteArray);

                    // If the order document has more than 1 page
                    if ($pageCount > 1) {
                        // Use the order as the source file so that the rest of the pages are imported
                        $this->fpdi->setSourceFile($disk->path($orderPdf));
                    }
                }
            }

            if (!$two_sided) {
                $this->importVoucher($byteArray);
            } elseif ($pageCount % 2 === 0) {
                $this->fpdi->AddPage();
            }

            // Delete the temporary pdf file
            $disk->delete($orderPdf);
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
        for($i = 1; $i <= $pages; $i++) {
            $this->importPage($i);
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