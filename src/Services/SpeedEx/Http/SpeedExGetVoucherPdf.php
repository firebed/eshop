<?php

namespace Eshop\Services\SpeedEx\Http;

use Error;
use Eshop\Services\SpeedEx\Enums\SpeedExPaperType;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SpeedExGetVoucherPdf extends SpeedExRequest
{
    protected string $action = 'GetBOLPdf';

    public function handle(array|string $voucher, SpeedExPaperType $paperType = SpeedExPaperType::A4, bool $splitPdfs = false): Collection|string
    {
        $vouchers = Arr::wrap($voucher);
        if (count($vouchers) > 20) {
            throw new Error("The maximum number of vouchers is 20.");
        }
    
        $response = $this->request([
            'paperType'  => $paperType->value,
            'perVoucher' => $splitPdfs,
            'voucherIDs' => Arr::wrap($voucher)
        ]);

        $vouchers = collect($response->GetBOLPdfResult ?? [])->first();
        if (is_array($vouchers)) {
            return collect($vouchers)->mapWithKeys(fn($v) => [$v->VoucherID => $v->pdf]);
        }

        return $vouchers->pdf ?? "";
    }
}