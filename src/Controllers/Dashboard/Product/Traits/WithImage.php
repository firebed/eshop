<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Illuminate\Http\UploadedFile;

trait WithImage
{
    protected function replaceImage($imageable, UploadedFile $image): void
    {
        $oldImage = $imageable->image;
        if ($oldImage) {
            $oldImage->delete();
        }

        $imageable->saveImage($image);
    }
}