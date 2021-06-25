<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\Vat;

trait CreatesEmptyProduct
{
    protected function makeProduct(): Product
    {
        $product = new Product;
        $product->visible = TRUE;
        $product->available = TRUE;
        $product->available_gt = 0;
        $product->display_stock = TRUE;
        $product->category_id = "";
        $product->manufacturer_id = "";
        $product->unit_id = "";
        $product->vat = optional(Vat::standard())->regime ?: '';
        $product->unit()->associate(Unit::firstWhere('name', 'Piece'));
        $product->discount = 0;
        $product->price = 0;
        $product->compare_price = 0;
        $product->stock = 0;
        $product->weight = 0;
        return $product;
    }
}
