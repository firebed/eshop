<?php


namespace App\Repository\Contracts;


use App\Models\Product\Product;

interface ProductContract
{
    public function updateStock(Product $product, float $stock): bool;

    public function addStock(Product $product, float $stock): bool;

    public function subtractStock(Product $product, float $stock): bool;

    public function save(Product $product): void;
}
