<?php

namespace Eshop\Actions\Product;

use Eshop\Actions\InsertWatermark;
use Eshop\Actions\Product\Traits\SavesProductProperties;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateProduct
{
    use SavesProductProperties;

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

        if ($product->isVariant()) {
            $product->options()->sync([]);
            $this->saveVariantOptions($product, $request->input('options'));
        } else {
            $this->syncVariantTypes($product, $request->input('variantTypes', []));

            $this->saveProperties($product, $request->input('properties', []));

            $product->collections()->sync($request->input('collections', []));
        }

        if ($request->hasFile('image')) {
            $this->replaceImage($product, $request->file('image'));
        }

        if (!$product->has_watermark && $product->image->hasConversion('wm')) {
            $product->image->deleteConversion('wm');
        } elseif ($product->has_watermark) {
            if ($request->hasFile('image')) {
                $image = $this->watermark->handle($request->file('image'));
                $product->image->addConversion('wm', $image);
            } elseif ($product->image) {
                $image = $this->watermark->handle($product->image->path());
                $product->image->addConversion('wm', $image);
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

    private function saveVariantOptions(Product $variant, array $options): void
    {
        foreach ($options as $variantTypeId => $option) {
            $variant->options()->attach($variantTypeId, [
                'value' => $option,
                'slug'  => slugify($option, '_')
            ]);
        }
    }

    private function syncVariantTypes(Product $product, array $data): void
    {
        $variantTypes = $product->variantTypes()->pluck('name', 'id');
        $deleteIds = $variantTypes->keys()->diff(array_column($data, 'id'));
        VariantType::whereKey($deleteIds)->delete();

        foreach ($data as $i => $variantType) {
            $product->variantTypes()->updateOrCreate(['id' => $variantType['id']], [
                'name'     => $variantType['name'],
                'slug'     => slugify($variantType['name']),
                'position' => $i
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