<?php

namespace Eshop\Actions\Product;

use Eshop\Actions\InsertWatermark;
use Eshop\Actions\Product\Traits\SavesProductProperties;
use Eshop\Actions\Product\Traits\SavesSaleChannels;
use Eshop\Actions\Product\Traits\SavesVariantOptions;
use Eshop\Actions\Product\Traits\SavesVariantTypes;
use Eshop\Models\Product\Product;
use Eshop\Requests\Dashboard\Product\ProductRequest;
use Eshop\Services\BarcodeService;

class StoreProduct
{
    use SavesProductProperties, SavesVariantOptions, SavesVariantTypes, SavesSaleChannels;

    public function __construct(private InsertWatermark $watermark,
                                private BarcodeService  $barcodeService)
    {
    }

    public function handle(ProductRequest $request): Product
    {
        $product = new Product();
        $product->fill($request->only($product->getFillable()));

        $this->fillBarcode($product);

        $product->save();

        $product->seo()->create($request->input('seo'));

        $this->storeVariantTypes($product, $request->input('variantTypes', []));

        $this->saveProperties($product, $request->input('properties', []));

        $this->saveSaleChannels($product, $request->input('channel_ids'), $request->input('channel_pricing'));
        
        $product->collections()->sync($request->input('collections', []));

        if ($request->hasFile('image')) {
            $product->saveImage($request->file('image'));
            if ($product->has_watermark) {
                $image = $this->watermark->handle($request->file('image'));
                if ($image) {
                    $product->image->addConversion('wm', $image);
                }
            }
        }
        $request->dd();
        if ($request->hasFile('attachment.file')) {
            $product->saveAttachment($request->file('attachment.file'), $request->input('attachment.title'));
        }

        return $product;
    }

    private function fillBarcode(Product $product): void
    {
        if (filled($product->barcode) || !$this->barcodeService->shouldFill()) {
            return;
        }

        $product->barcode = $this->barcodeService->generateForProduct($product->category_id);
    }
}