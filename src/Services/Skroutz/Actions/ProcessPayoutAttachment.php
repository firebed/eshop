<?php

namespace Eshop\Services\Skroutz\Actions;

use Exception;
use Illuminate\Support\Collection;
use Smalot\PdfParser\Parser;
use Webklex\PHPIMAP\Attachment;

class ProcessPayoutAttachment
{
    /**
     * @throws Exception
     */
    public function handle(Attachment $attachment): Collection
    {
        if (!$this->validate($attachment)) {
            return collect();
        }

        $matches = $this->extractPayments($attachment->getContent());

        $payouts = collect();
        for ($i = 0; $i < count($matches[0]); $i++) {
            $payouts->put($matches[0][$i], [
                'payoutTotal' => parseFloat($matches[2][$i], ','),
                'fees'        => parseFloat($matches[3][$i], ','),
                'orderTotal'  => parseFloat($matches[4][$i], ','),
            ]);
        }

        return $payouts;
    }

    /**
     * @throws Exception
     */
    private function extractPayments(string $file)
    {
        $parser = new Parser();
        $pdf = $parser->parseContent($file);
        $text = $pdf->getText();

        $orderId = "([^\s]*)";
        $date = "(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})";
        preg_match_all("/^$orderId.*$date\s(\d+,\d{2})[^0-9]*(\d+,\d{2})[^0-9]*(\d+,\d{2}).*$/m", $text, $matches);
        array_shift($matches);

        return $matches;
    }

    private function validate(Attachment $attachment): bool
    {
        return $attachment->getMimeType() === "application/pdf";
    }
}