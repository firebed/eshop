<?php

namespace Eshop\Actions\Product;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class BarcodeGenerator
{
    public function handle(Category|int|null $category_id = null, Product|int|null $product_id = null, Product|int|null $variant_id = null): string
    {
        // When editing existing variant
        if ($variant_id !== null) {
            return $this->forExistingVariant($variant_id);
        }
        
        // When creating new variant
        if ($category_id === null && $product_id !== null) {
            return $this->forNewVariant($product_id);
        }

        // When creating new product
        if ($category_id !== null && $product_id === null) {
            return $this->forNewProduct($category_id);
        }

        // When editing existing product
        if ($product_id !== null) {
            return $this->forExistingProduct($product_id);
        }

        // Fallback
        $next = Product::max('id') + 1;
        return $this->generate($next);
    }

    private function forExistingVariant(Product|int $variant_id): string
    {
        $variant = is_int($variant_id) ? Product::find($variant_id) : $variant_id;

        return $this->isNested()
            ? $this->generate($variant->category_id, $variant->parent_id, $variant->id)
            : $this->generate($variant->category_id, $variant->id);
    }

    private function forNewVariant(Product|int $product_id): string
    {
        $product = is_int($product_id) ? Product::find($product_id) : $product_id;
        $next = $product->variants()->max('id') + 1;

        return $this->isNested()
            ? $this->generate($product->category_id, $product->id, $next)
            : $this->generate($product->category_id, $next);
    }

    private function forNewProduct(Category|int $category_id): string
    {
        $category = is_int($category_id) ? Category::find($category_id) : $category_id;
        $next = $category->products()->max('id') + 1;
        return $this->generate($category->id, $next);
    }

    private function forExistingProduct(Product|int $product_id): string
    {
        $product = is_int($product_id) ? Product::find($product_id) : $product_id;
        return $this->generate($product->category_id, $product->id);
    }

    private function generate(...$parts): string
    {
        $this->prepend($parts);
        return implode($this->separator(), $parts);
    }

    private function separator(): string
    {
        return eshop('barcode.separator', '-');
    }

    private function prepend(array &$parts): void
    {
        $prepend = eshop('barcode.prepend');
        if (filled($prepend)) {
            array_unshift($parts, $prepend);
        }
    }

    private function isNested(): bool
    {
        return eshop('barcode.nested', false);
    }
}