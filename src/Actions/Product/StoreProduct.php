<?php

namespace Eshop\Actions\Product;

use Eshop\Actions\Product\Traits\SavesProductProperties;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Requests\Dashboard\Product\ProductRequest;
use Eshop\Services\BarcodeService;

class StoreProduct
{
    use SavesProductProperties;

    private BarcodeService $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function handle(ProductRequest $request): Product
    {
        $product = new Product();
        $product->fill($request->only($product->getFillable()));

        $this->fillBarcode($product);

        $product->save();

        $product->seo()->create($request->input('seo'));

        if ($request->filled('variantTypes')) {
            $this->saveVariantTypes($product, $request->input('variantTypes'));
        }

        if ($request->filled('properties')) {
            $this->saveProperties($product, $request->input('properties'));
        }

        $product->collections()->sync($request->input('collections', []));

        if ($request->hasFile('image')) {
            $product->saveImage($request->file('image'));
            if ($request->boolean('watermark')) {
                $product->addWatermark();
            }
        }

        return $product;
    }

    private function saveVariantTypes(Product $product, array $data): void
    {
        $variantTypes = collect($data)->transform(fn($v, $i) => new VariantType([
            'name'     => $v['name'],
            'slug'     => slugify($v['name']),
            'position' => $i
        ]));

        $product->variantTypes()->saveMany($variantTypes);
    }

    private function fillBarcode(Product $product): void
    {
        if (filled($product->barcode) || !$this->barcodeService->shouldFill()) {
            return;
        }

        $product->barcode = $this->barcodeService->generateForProduct($product->category_id);
    }
}