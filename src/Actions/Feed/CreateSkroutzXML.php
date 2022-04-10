<?php

namespace Eshop\Actions\Feed;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class CreateSkroutzXML
{
    public function handle(): SimpleXMLElement
    {
        $locale = config('app.locale');

//        $defaultShippingMethod = DB::table('country_shipping_method')
//            ->where('country_id', 1)
//            ->where('visible', true)
//            ->where('cart_total', 0)
//            ->where('inaccessible_area_fee', 0)
//            ->whereNotIn('shipping_method_id', [2, 6]) // KTEL, Store
//            ->orderBy('position')
//            ->fist();
//
//        $payOnDelivery = DB::table('country_payment_method')
//            ->where('country_id', 1)
//            ->where('visible', true)
//            ->where('cart_total', 0)
//            ->where('payment_method_id', 4) // Pay on delivery
//            ->orderBy('position')
//            ->fist();

        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><products standalone='yes' version='1.0' />");
        $xml->addChild('datetime', now()->format('Y-m-d H:i:s'));
        $xml->addChild('title', config('app.name') . ' product feed');
        $xml->addChild('link', config('app.url'));

        $categories = DB::table('categories')
            ->where('visible', true)
            ->get(['id', 'parent_id', 'slug'])
            ->keyBy('id');

        $products = DB::table('products')
            ->where('visible', true)
            ->where('net_value', '>', 0)
            ->whereNull('deleted_at')
            ->get(['id', 'parent_id', 'category_id', 'manufacturer_id', 'vat', 'weight', 'net_value', 'stock', 'available', 'available_gt', 'has_watermark', 'sku', 'slug', 'has_variants'])
            ->keyBy('id');

        $products = $products->reject(fn($p) => !$categories->has($p->category_id) || ($p->parent_id !== null && !$products->has($p->parent_id)));

        $categoriesSeo = DB::table('seo')
            ->where('locale', $locale)
            ->where('seo_type', 'category')
            ->whereIntegerInRaw('seo_id', $categories->keys())
            ->get(['seo_id', 'title'])
            ->keyBy('seo_id');

        $productsSeo = DB::table('seo')
            ->where('locale', $locale)
            ->where('seo_type', 'product')
            ->whereIntegerInRaw('seo_id', $products->keys())
            ->get(['seo_id', 'title', 'description'])
            ->keyBy('seo_id');

        $images = DB::table('images')
            ->where('imageable_type', 'product')
            ->whereNull('collection')
            ->whereIntegerInRaw('imageable_id', $products->keys())
            ->get(['imageable_id', 'disk', 'src', 'conversions'])
            ->keyBy('imageable_id');

        $manufacturers = DB::table('manufacturers')->get(['id', 'name'])->keyBy('id');

        $variantTypes = DB::table('variant_types')->get(['id', 'product_id', 'slug'])->groupBy('product_id')->map(fn($c) => $c->keyBy('slug'));

        $productVariantTypes = DB::table('product_variant_type')
            ->join('translations', function (JoinClause $q) use ($locale) {
                $q->on('translations.translatable_id', '=', 'product_variant_type.id');
                $q->where('translations.locale', $locale);
                $q->where('translations.translatable_type', 'product_variant_type');
                $q->where('translations.cluster', 'name');
            })
            ->whereIntegerInRaw('product_id', $products->keys())
            ->get(['product_id', 'variant_type_id', 'translation'])
            ->groupBy('product_id')
            ->map(fn($g) => $g->keyBy('variant_type_id'));

        foreach ($products as $product) {
            if ($product->has_variants || !isset($images[$product->id])) {
                continue;
            }

            $image = $images[$product->id];
            $category = $categories[$product->category_id];
            $parent = $products[$product->parent_id] ?? null;
            $categorySeo = $categoriesSeo[$category->id];
            $productSeo = $productsSeo[$product->id];
            $parentSeo = $productsSeo[$product->parent_id] ?? null;

            $item = $xml->addChild("product");
            if ($item === null) {
                continue;
            }

            $item->addChild("id", $product->id);

            $breadcrumbs = [$categorySeo->title];
            $parent_id = $category->parent_id;
            while ($parent_id) {
                $p = $categories->get($parent_id);
                array_unshift($breadcrumbs, $categoriesSeo[$p->id]->title);
                $parent_id = $p->parent_id;
            }
            $item->addChild('category', e(implode(' > ', $breadcrumbs)));
            $item->addChild('category_id', $category->id);

            if ($product->has_watermark) {
                $conversions = json_decode($image->conversions, false);
                $url = Storage::disk($image->disk)->url($conversions->md->src ?? $image->src);
            } else {
                $url = Storage::disk($image->disk)->url($image->src);
            }
            $item->addChild("image", e($url));

            $item->addChild("name", e($productSeo->title));
            $item->addChild("description", e($product->parent_id === null ? $productSeo->description : $parentSeo->description));
            $item->addChild("link", e(route('products.show', [$locale, $category->slug, $product->slug])));
            $item->addChild("price", number_format($product->net_value, 2));
            $item->addChild("vat", number_format($product->vat * 100, 2));

            if ($this->canPurchase($product, $parent)) {
                $item->addChild("instock", 'Y');
                $item->addChild("availability", e('Άμεση παραλαβή / Παράδοση σε 1 - 3 ημέρες'));
            } else {
                $item->addChild("instock", 'N');
                $item->addChild("availability", e('Κατόπιν παραγγελίας'));
            }

            if (filled($product->sku)) {
                $item->addChild("mpn", e($product->sku));
            }

            if ($product->weight !== 0) {
                $item->addChild("weight", $product->weight);
            }

            if (filled($product->manufacturer_id)) {
                $manufacturer = $manufacturers[$product->manufacturer_id];
                $item->addChild("manufacturer", $manufacturer->name);
            }

            if ($product->parent_id !== null) {
                $types = $variantTypes[$product->parent_id] ?? null;
                if ($types === null) {
                    break;
                }

                $colorVariant = $types->get('xrwma');
                if ($colorVariant !== null) {
                    $color = $productVariantTypes[$product->id][$colorVariant->id];
                    $item->addChild("color", e($color->translation));
                }

                // Single size
                $sizeVariant = $types->get('megethos');
                if ($sizeVariant !== null) {
                    $size = $productVariantTypes[$product->id][$sizeVariant->id];
                    $item->addChild("size", e(str($size->translation)->replace(',', '.')));
                }

//                Grouped sizes
//                $sizeVariant = $types->get('megethos');
//                if ($sizeVariant !== null) {
//                    $variant_ids = $products->filter(fn($p) => $p->parent_id === $product->parent_id)->keys(); // Get the products with same parent_id
//                    $differentSizes = $productVariantTypes->only($variant_ids)->collapse()->where('variant_type_id', $sizeVariant->id)->pluck('translation');
//                    $differentSizes = $differentSizes->map(fn($name) => str($name)->replace(',', '.'))->join(', ');
//                    $item->addChild("size", e($differentSizes));
//                }
            }
        }

        return $xml;
    }

    private function canPurchase($product, $parent = null): bool
    {
        if ($parent !== null && !$parent->available) {
            return false;
        }

        return $product->available
            && ($product->available_gt === null || ($product->stock - 1 >= $product->available_gt));
    }
}
