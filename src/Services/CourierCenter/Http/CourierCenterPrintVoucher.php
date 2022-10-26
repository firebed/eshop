<?php

namespace Eshop\Services\CourierCenter\Http;

use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;
use Illuminate\Support\Arr;

class CourierCenterPrintVoucher extends CourierCenterRequest
{
    public const FORMAT_PDF                = "pdf";
    public const FORMAT_CLEAN              = "clean";
    public const FORMAT_SINGLE_PDF         = "singlepdf";
    public const FORMAT_SINGLE_CLEAN       = "singleclean";
    public const FORMAT_SINGLE_PDF_100x150 = "singlepdf_100x150";
    public const FORMAT_SINGLE_PDF_100x170 = "singlepdf_100x170";

    protected string $action = 'Voucher';

    /**
     * @throws CourierCenterException
     */
    public function handle(mixed $vouchers, string $template = self::FORMAT_PDF): string
    {
        $vouchers = Arr::wrap($vouchers);

        $response = $this->request(array_filter([
            'ShipmentNumber'  => implode(',', $vouchers),
            'TrackingNumbers' => null,
            'VoucherFormat'   => 'PDF',
            'Template'        => $template
        ]));

        return base64_decode($response['Voucher']); // Base64Binary
    }
}