<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Eshop\Models\Product\Vat;

trait CreatesEmptyProduct
{
    protected function makeProduct(): Product
    {
        $product = new Product;
        $product->visible = true;
        $product->available = true;
        $product->display_stock = true;
        $product->category_id = "";
        $product->manufacturer_id = "";
        $product->unit_id = "";
        $product->vat = Vat::standard()->regime;
        $product->discount = 0;
        $product->price = 0;
        $product->compare_price = 0;
        $product->stock = 0;
        $product->weight = 0;
        return $product;
    }
}
