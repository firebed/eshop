<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Order $order): Renderable
    {
        // Return 404 if the category or the variant's parent is hidden
        abort_unless($category->visible || ($product->isVariant() && $product->parent->visible), 404);

        // Early return if the product is hidden and the auth user can manage the product
        // This way the user will have a special option to make the product visible again
        if (!$product->visible && auth()->user()?->can('Manage products')) {
            return $this->view('product.show-hidden', compact('category', 'product'));
        }

        abort_unless($product->visible, 404);

        $quantity = 0;
        $variantImage = null;
        if ($product->has_variants) {
            $product->load(['variants' => fn($q) => $q->visible()->with('parent', 'image', 'options.translations')]);
            (new Collection($product->variants->pluck('options')->collapse()->pluck('pivot')))->load('translations');

            $variants = eshop('variants.sort.available_first')
                ? $product->variants
                    ->groupBy(fn($p) => $p->canBeBought())
                    ->sortKeysDesc()
                    ->map(fn($c) => $c->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE))
                    ->flatten()
                : $product->variants->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE);

            $variantImage = $this->getVariantImage($product, $variants, request()->query('options', ''));
        } else {
            $quantity = $order->getProductQuantity($product);
        }

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return $this->view($product->isVariant() ? 'product.show-variant' : 'product.show', [
            'category'     => $category,
            'product'      => $product,
            'variants'     => $variants ?? null,
            'images'       => $product->images('gallery')->get(),
            'variantImage' => $variantImage,
            'quantity'     => $quantity,
            'properties'   => $product->properties()->visible()->with('translations')->get()->unique(),
            'choices'      => $product->choices()->with('translations')->get(),
        ]);
    }

    private function getUniqueVariantOptions($variants): \Illuminate\Support\Collection
    {
        return $variants
            ->pluck('options')
            ->collapse()
            ->groupBy('pivot.variant_type_id')
            ->map(fn($g) => $g->unique('pivot.name'))
            ->collapse();
    }

    private function getVariantImage(Product $product, $variants, string $options): ?string
    {
        $filters = explode('-', $options);

        $variantId = $variants
            ->pluck('options')
            ->collapse()
            ->whereIn('pivot.slug', $filters)
            ->groupBy('pivot.product_id')
            ->filter(fn($c) => $c->count() === $product->variantTypes->count())
            ->keys()
            ->first();

        if ($variantId !== null) {
            $variant = Product::find($variantId);
            return $variant->image ? $variant->image->url($variant->has_watermark ? 'wm' : null) : null;
        }

        $uniqueOptions = $this->getUniqueVariantOptions($variants);

        foreach ($filters as $f) {
            $option = $uniqueOptions->firstWhere('pivot.slug', $f);
            if ($option !== null && $option->slug === 'xrwma') {
                $variant = Product::find($option->pivot->product_id);
                return $variant->image ? $variant->image->url($variant->has_watermark ? 'wm' : null) : null;
            }
        }
        
        return null;
    }
}
