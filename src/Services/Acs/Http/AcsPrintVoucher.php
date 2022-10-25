<?php

namespace Eshop\Services\Acs\Http;

use Error;
use Illuminate\Support\Arr;

class AcsPrintVoucher extends AcsRequest
{
    public const THERMAL = 1;
    public const LASER   = 2;

    protected string $action = 'ACS_Print_Voucher';

    /**
     * <p>Χρησιμοποιείται για τη δημιουργία και την εκτύπωση των vouchers σε μορφή PDF
     * αναλόγως του εκτυπωτή που έχει ο πελάτης (laser ή θερμικό).</p>
     * <p>Τα vouchers πρέπει να τυπώνονται πριν την εκτύπωση της λίστας παραλαβής (Pick up
     * list) γιατί μετά δεν είναι εφικτή η εκτύπωση τους.</p>
     *
     * @param mixed $vouchers
     * @param int   $printType
     * @param int   $startPosition
     * @return int|array
     */
    public function handle(mixed $vouchers, int $printType = self::LASER, int $startPosition = 1): int|array
    {
        $vouchers = Arr::wrap($vouchers);
        $vouchers = array_map('trim', $vouchers);

        if (count($vouchers) > 10) {
            throw new Error("Max 10");
        }
        
        [$value] = $this->request([
            "Voucher_No"     => implode(',', $vouchers),
            "Print_Type"     => 2,
            "Start_Position" => 1
        ]);

        dd($value);
        
        return $value['ACSObjectOutput'] ?? [];
    }

    protected function parseOutput($output)
    {
        return $output;
    }
}