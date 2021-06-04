<?php


namespace App\Models\Cart\Relations;


use App\Models\Cart\CartProduct;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;

class TempProducts
{
    private const GUEST_KEY = 'guest-cart';

    public function syncWithoutDetaching($ids): void
    {
        $ids = $this->parseIds($ids);
        foreach ($ids as $key => $data) {
            session()->put(self::GUEST_KEY . ".products.$key", $data + ['product_id' => $key]);
        }
    }

    public function updateExistingPivot($id, array $attributes): void
    {
        $pivot = session(self::GUEST_KEY . ".products.$id");
        session()->put(self::GUEST_KEY . ".products.$id", array_merge($pivot, $attributes));
    }

    public function detach($ids): void
    {
        $ids = $this->parseIds($ids);

        foreach ($ids as $id) {
            session()->forget(self::GUEST_KEY . ".products.$id");
        }
    }

    public function getQuantity(int $product_id): int
    {
        return session(self::GUEST_KEY . ".products.$product_id.quantity", 0);
    }

    public function count(): int
    {
        return count(session(self::GUEST_KEY . ".products", []));
    }

    protected function parseIds($value): array
    {
        if ($value instanceof Model) {
            return [$value->id];
        }

        if ($value instanceof Collection) {
            return $value->pluck('id')->all();
        }

        if ($value instanceof BaseCollection) {
            return $value->toArray();
        }

        return (array)$value;
    }

    public function getResults(): Collection
    {
        $pivotItems = collect(session(self::GUEST_KEY . '.products', []))->transform(fn($item) => new CartProduct($item));
        if ($pivotItems->isNotEmpty()) {
            $products = Product::findMany($pivotItems->pluck('product_id'));
            foreach ($products as $product) {
                $product->setRelation('pivot', $pivotItems->firstWhere('product_id', $product->id));
            }
            return $products;
        }
        return Collection::make();
    }

    public static function forgetSession(): void
    {
        session()->forget(self::GUEST_KEY);
    }
}
