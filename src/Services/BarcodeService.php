<?php

namespace Eshop\Services;

use Eshop\Actions\Product\BarcodeGenerator;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class BarcodeService
{
    public function shouldFill(): bool
    {
        return eshop('barcode.fill', false);
    }

    public function generateForProduct(Category|int|null $category_id = null, Product|int|null $product_id = null): string
    {
        return (new BarcodeGenerator())->handle($category_id, $product_id);
    }

    public function generateForVariant(Product|int $product_id, Product|int|null $variant_id = null): string
    {
        return (new BarcodeGenerator())->handle(null, $product_id, $variant_id);
    }
}