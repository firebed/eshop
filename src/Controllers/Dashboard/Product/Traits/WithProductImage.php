<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Illuminate\Http\UploadedFile;

trait WithProductImage
{
    protected function replaceProductImage(Product $product, UploadedFile $image): void
    {
        $oldImage = $product->image;
        if ($oldImage) {
            $oldImage->delete();
        }

        $product->saveImage($image);
    }
}