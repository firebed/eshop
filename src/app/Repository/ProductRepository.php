<?php


namespace App\Repository;


use App\Models\Product\Product;
use App\Repository\Contracts\ProductContract;

class ProductRepository implements ProductContract
{
    /**
     * Sets the product's stock directly
     *
     * @param Product $product
     * @param float   $stock
     * @return bool
     */
    public function updateStock(Product $product, float $stock): bool
    {
        $product->stock = $stock;
        return $product->save();
    }

    /**
     * Adds the given stock amount to the product
     *
     * @param Product $product
     * @param float   $stock
     * @return bool
     */
    public function addStock(Product $product, float $stock): bool
    {
        return $this->updateStock($product, $product->stock + $stock);
    }

    /**
     * Removes the given stock amount from the product
     *
     * @param Product $product
     * @param float   $stock
     * @return bool
     */
    public function subtractStock(Product $product, float $stock): bool
    {
        return $this->updateStock($product, $product->stock - $stock);
    }

    public function save(Product $product): void
    {
        $shouldUpdateVariants = $product->has_variants && $product->isDirty('category_id', 'manufacturer_id');

        $product->save();
        if ($shouldUpdateVariants) {
            $this->updateVariants($product->id, [
                'category_id'     => $product->category_id,
                'manufacturer_id' => $product->manufacturer_id
            ]);
        }
    }

    private function updateVariants(int $product_id, array $data): void
    {
        Product::query()->where('parent_id', $product_id)->update($data);
    }
}
