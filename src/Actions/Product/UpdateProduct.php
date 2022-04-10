<?php

namespace Eshop\Actions\Product;

use Eshop\Actions\InsertWatermark;
use Eshop\Actions\Product\Traits\SavesProductProperties;
use Eshop\Actions\Product\Traits\SavesSaleChannels;
use Eshop\Actions\Product\Traits\SavesVariantOptions;
use Eshop\Actions\Product\Traits\SavesVariantTypes;
use Eshop\Models\Product\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateProduct
{
    use SavesProductProperties, SavesVariantOptions, SavesVariantTypes, SavesSaleChannels;

    public function __construct(private InsertWatermark $watermark)
    {
    }

    public function handle(Product $product, FormRequest $request): void
    {
        $product->fill($request->only($product->getFillable()));

        if ($product->has_variants) {
            $this->touchVariants($product, $request);
        }

        $product->save();

        $product->seo()->updateOrCreate([], $request->input('seo'));

        $this->saveSaleChannels($product, $request->input('channels'));
        
        if ($product->isVariant()) {
            $this->saveVariantOptions($product, $request->input('options'));
        } else {
            $this->saveVariantTypes($product, $request->input('variantTypes', []));

            $this->saveProperties($product, $request->input('properties', []));

            $product->collections()->sync($request->input('collections', []));
        }

        if ($request->hasFile('image')) {
            $this->replaceImage($product, $request->file('image'));
        }

        if (!$product->has_watermark && $product->image?->hasConversion('wm')) {
            $product->image->deleteConversion('wm');
        } elseif ($product->has_watermark) {
            if ($request->hasFile('image')) {
                $image = $this->watermark->handle($request->file('image'));
                if ($image) {
                    $product->image->addConversion('wm', $image);
                }
            } elseif ($product->image) {
                $image = $this->watermark->handle($product->image->path());
                if ($image) {
                    $product->image->addConversion('wm', $image);
                }
            }
        }
    }

    private function touchVariants(Product $product, FormRequest $request): void
    {
        if (!$product->has_variants) {
            return;
        }

        if ($product->isDirty('category_id')) {
            $product->variants()->update([
                'category_id' => $request->input('category_id')
            ]);
        }

        if ($product->isDirty('manufacturer_id')) {
            $product->variants()->update([
                'manufacturer_id' => $request->input('manufacturer_id')
            ]);
        }

        if ($product->isDirty('unit_id')) {
            $product->variants()->update([
                'unit_id' => $request->input('unit_id')
            ]);
        }
    }

    private function replaceImage($imageable, UploadedFile $image): void
    {
        $oldImage = $imageable->image;
        $oldImage?->delete();
        $imageable->unsetRelation('image');

        $imageable->saveImage($image);
    }
}