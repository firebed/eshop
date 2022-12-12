<?php

namespace Eshop\Services\Skroutz\Imports;

use Exception;
use Illuminate\Support\Collection;
use Smalot\PdfParser\Parser;

class SkroutzPayoutsImport
{
    /**
     * @throws Exception
     */
    public function handle(string $path): Collection
    {
        $matches = $this->extractPayments($path);

        $payouts = collect();
        
        $size = count($matches[0]);
        for ($i = 0; $i < $size; $i++) {
            $payouts->put($matches[0][$i], [
                'customer_name' => null,
                'fees'          => parseFloat($matches[3][$i], ','),
                'total'         => parseFloat($matches[2][$i], ','),
            ]);
        }

        return $payouts;
    }

    /**
     * @throws Exception
     */
    private function extractPayments(string $path)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        $text = $pdf->getText();

        $orderId = "([^\s]*)";
        $date = "(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})";
        preg_match_all("/^$orderId.*$date\s(\d+,\d{2})[^0-9]*(\d+,\d{2}).*$/m", $text, $matches);
        array_shift($matches);

        return $matches;
    }
}