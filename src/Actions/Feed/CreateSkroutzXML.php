<?php

namespace Eshop\Actions\Feed;

use Eshop\Models\Product\Channel;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class CreateSkroutzXML
{
    public function handle(): ?SimpleXMLElement
    {
        $locale = config('app.locale');

        $skroutz = Channel::firstWhere('name', 'Skroutz');
        if (!$skroutz) {
            return null;
        }

        $categories = DB::table('categories')
            ->where('visible', true)
            ->get(['id', 'parent_id', 'slug'])
            ->keyBy('id');

        $inSkroutz = DB::table('channel_product')
            ->where('channel_id', $skroutz->id)
            ->pluck('channel_id', 'product_id');

        $parents = DB::table('products')
            ->where('visible', true)
            ->where('has_variants', true)
            ->whereIntegerInRaw('category_id', $categories->keys())
            ->whereNull('deleted_at')
            ->join('translations', function (JoinClause $q) use ($locale) {
                $q->on('translations.translatable_id', '=', 'products.id');
                $q->where('translations.locale', $locale);
                $q->where('translations.translatable_type', 'product');
                $q->where('translations.cluster', 'name');
            })
            ->get(['products.id', 'translations.translation', 'available', 'slug'])
            ->keyBy('id');

        $products = DB::table('products')
            ->where('visible', true)
            ->where('has_variants', false)
            ->whereIntegerInRaw('category_id', $categories->keys())
            ->whereIntegerInRaw('products.id', $inSkroutz->keys())
            ->whereNull('deleted_at')
            ->leftJoin('translations', function (JoinClause $q) use ($locale) {
                $q->on('translations.translatable_id', '=', 'products.id');
                $q->where('translations.locale', $locale);
                $q->where('translations.translatable_type', 'product');
                $q->where('translations.cluster', 'name');
            })
            ->get(['products.id', 'translations.translation', 'parent_id', 'category_id', 'manufacturer_id', 'vat', 'weight', 'net_value', 'stock', 'available', 'available_gt', 'has_watermark', 'sku', 'slug', 'has_variants'])
            ->filter(fn($p) => $p->parent_id === null || $parents->has($p->parent_id))
            ->filter(fn($p) => $this->canPurchase($p, $parents[$p->parent_id] ?? null))
            ->keyBy('id');

        $categoriesSeo = DB::table('seo')
            ->where('locale', $locale)
            ->where('seo_type', 'category')
            ->whereIntegerInRaw('seo_id', $categories->keys())
            ->get(['seo_id', 'title'])
            ->keyBy('seo_id');

        $productsSeo = DB::table('seo')
            ->where('locale', $locale)
            ->where('seo_type', 'product')
            ->whereIntegerInRaw('seo_id', $products->keys()->merge($parents->keys()))
            ->get(['seo_id', 'title', 'description'])
            ->keyBy('seo_id');

        $images = DB::table('images')
            ->where('imageable_type', 'product')
            ->whereNull('collection')
            ->whereIntegerInRaw('imageable_id', $products->keys())
            ->get(['imageable_id', 'disk', 'src', 'conversions'])
            ->keyBy('imageable_id');

        $manufacturers = DB::table('manufacturers')->get(['id', 'name'])->keyBy('id');

        $variantTypes = DB::table('variant_types')
            ->whereIntegerInRaw('product_id', $parents->keys())
            ->get(['id', 'product_id', 'slug'])
            ->groupBy('product_id')
            ->map(fn($c) => $c->keyBy('slug'));

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

        $create = static function ($product, $color = '') use ($categories, $images, $parents, $categoriesSeo, $productsSeo, $locale, $manufacturers) {
            $category = $categories[$product->category_id];
            $categorySeo = $categoriesSeo[$category->id];

            $breadcrumbs = [$categorySeo->title];
            $parent_id = $category->parent_id;
            while ($parent_id) {
                $p = $categories->get($parent_id);
                array_unshift($breadcrumbs, $categoriesSeo[$p->id]->title);
                $parent_id = $p->parent_id;
            }

            $image = $images[$product->id];

            $link = $product->parent_id !== null
                ? route('products.show', [$locale, $category->slug, $parents[$product->parent_id]->slug])
                : route('products.show', [$locale, $category->slug, $product->slug]);

            $item = [
                'id'           => $product->id,
                'category'     => implode(' > ', $breadcrumbs),
                'category_id'  => $category->id,
                'name'         => trim(($product->parent_id !== null ? $parents[$product->parent_id]->translation . ' ' : "") . $product->translation),
                'image'        => Storage::disk($image->disk)->url($image->src),
                'description'  => $productsSeo[$product->parent_id ?? $product->id]->description,
                'link'         => $link,
                'price'        => number_format($product->net_value, 2),
                'vat'          => number_format($product->vat * 100, 2),
                'instock'      => 'Y',
                'availability' => 'Παράδοση σε 1 - 3 ημέρες',
                'mpn'          => $product->mpn ?? $product->sku,
                'weight'       => $product->weight,
                'manufacturer' => $manufacturers[$product->manufacturer_id]->name ?? 'OEM'
            ];

            if (filled($color)) {
                $item['name'] .= ' ' . $color;
                $item['color'] = $color;
            }

            return $item;
        };

        $items = [];
        foreach ($products as $product) {
            // The product is autonomous
            if ($product->parent_id === null && !$product->has_variants) {
                $items[$product->id] = $create($product);
                continue;
            }

            // The product is variant
            // Get the available variant types
            $types = $variantTypes->get($product->parent_id);

            // If the variant has color option
            if ($types->has('xrwma')) {
                $colorVariant = $types->get('xrwma');
                $color = $productVariantTypes[$product->id][$colorVariant->id]->translation;

                if (!isset($items[$product->parent_id][$color])) {
                    $items[$product->parent_id][$color] = $create($product, $color);
                }

                if ($types->has('megethos')) {
                    $sizeVariant = $types->get('megethos');
                    $size = e($productVariantTypes[$product->id][$sizeVariant->id]->translation);

                    $items[$product->parent_id][$color]['size'][] = str_replace(',', '.', $size);
                }

                continue;
            }

            if ($types->has('megethos')) {
                $sizeVariant = $types->get('megethos');
                $size = e($productVariantTypes[$product->id][$sizeVariant->id]->translation);

                if (!isset($items[$product->parent_id])) {
                    $items[$product->parent_id] = $create($product);
                }

                $items[$product->parent_id]['size'][] = str_replace(',', '.', $size);
            }
        }

        return $this->createXml($items);
    }

    private function createXml(array $items): SimpleXMLElement
    {
        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><products standalone='yes' version='1.0' />");
        $xml->addChild('datetime', now()->format('Y-m-d H:i:s'));
        $xml->addChild('title', config('app.name') . ' product feed');
        $xml->addChild('link', config('app.url'));

        foreach ($items as $item) {
            if (isset($item['id'])) {
                $this->createProductXml($xml, $item);
            } else {
                foreach ($item as $v) {
                    $this->createProductXml($xml, $v);
                }
            }
        }

        return $xml;
    }

    private function createProductXml(SimpleXMLElement $root, $item): void
    {
        $node = $root->addChild("product");
        foreach ($item as $key => $value) {
            $node?->addChild($key, $key === 'size' ? implode(', ', array_map('e', $value)) : e($value));
        }
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
