<?php

namespace Eshop\Actions\Product;

use Eshop\Actions\InsertWatermark;
use Eshop\Actions\Product\Traits\SavesProductProperties;
use Eshop\Actions\Product\Traits\SavesSaleChannels;
use Eshop\Actions\Product\Traits\SavesVariantOptions;
use Eshop\Actions\Product\Traits\SavesVariantTypes;
use Eshop\Models\Product\Product;
use Eshop\Requests\Dashboard\Product\VariantRequest;
use Eshop\Services\BarcodeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreVariant
{
    use SavesProductProperties, SavesVariantOptions, SavesVariantTypes, SavesSaleChannels;

    public function __construct(private InsertWatermark $watermark,
                                private BarcodeService  $barcodeService)
    {
    }

    public function handle(VariantRequest $request, Product $parent): Product
    {
        $variant = $parent->replicate(['slug', 'mpn', 'has_variants', 'variants_display', 'preview_variants', 'net_value']);
        
        $variant->fill($request->only($variant->getFillable()));

        $this->fillBarcode($variant, $parent);

        $parent->variants()->save($variant);

        $variant->seo()->create($request->input('seo'));

        $this->saveSaleChannels($product, $request->input('channel_ids'));
        
        $this->saveVariantOptions($variant, $request->input('options'));

        if ($request->hasFile('image')) {
            $variant->saveImage($request->file('image'));
            if ($variant->has_watermark) {
                $image = $this->watermark->handle($request->file('image'));
                if ($image) {
                    $variant->image->addConversion('wm', $image);
                }
            }
        }
        
        return $variant;
    }

    private function fillBarcode(Product $variant, Product $parent): void
    {
        if (filled($variant->barcode) || !$this->barcodeService->shouldFill()) {
            return;
        }

        $variant->barcode = $this->barcodeService->generateForVariant($parent);
    }
}