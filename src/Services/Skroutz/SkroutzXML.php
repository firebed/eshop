<?php

namespace Eshop\Services\Skroutz;

use Eshop\Models\Product\Channel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class SkroutzXML
{
    private SimpleXMLElement $xml;

    private Collection $skroutzPrices;
    
    public function __construct(private readonly Collection $categories)
    {
        $skroutz = Channel::firstWhere('name', 'Skroutz');
        $this->skroutzPrices = DB::table('channel_product')
            ->where('channel_id', $skroutz->id)
            ->selectRaw("product_id, ROUND(price * (1 - discount), 2) as price")
            ->pluck('price', 'product_id');
        
        $this->xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><products standalone='yes' version='1.0' />");
        $this->xml->addChild('datetime', now()->format('Y-m-d H:i:s'));
        $this->xml->addChild('title', config('app.name') . ' product feed');
        $this->xml->addChild('link', config('app.url'));
    }

    public function getXML(): SimpleXMLElement
    {
        return $this->xml;
    }

    public function addSimpleProduct($product, string $uniqueId = null, string $name = null, string $link = null, string $color = null, string $size = null): void
    {
        $category = $this->categories->get($product->category_id);
        $node = $this->xml->addChild('product');
        
        $this->addSharedProperties($node, $product, $uniqueId, $name, $color);
        $node->addChild('link', e($link ?? route('products.show', ['el', $category->slug, $product->slug])));
        $node->addChild('image', e($this->getImage($product)));
        $node->addChild('quantity', (int)$product->stock);
        $node->addChild('mpn', $product->mpn);

        if (filled($size)) {
            $node->addChild('size', $size);
        }
    }

    public function addProductWithSizeVariations($product, string $uniqueId = null, string $name = null, string $link = null, string $color = null, Collection $sizeVariations = null): void
    {
        $category = $this->categories->get($product->category_id);
        $node = $this->xml->addChild('product');

        $sample = $sizeVariations->sortByDesc('net_value')->first();
        
        $this->addSharedProperties($node, $sample, $uniqueId ?? $product->id, $name ?? $product->translation, $color);
        $node->addChild('link', e($link ?? route('products.show', ['el', $category->slug, $product->slug])));
        $node->addChild('image', e($this->getImage($sample)));
        $node->addChild('quantity', $sizeVariations->sum('stock'));
        $node->addChild('size', $sizeVariations->pluck('size')->join(', '));
        $node->addChild('mpn', $product->mpn);

        $variations = $node->addChild('variations');
        foreach ($sizeVariations as $variation) {
            $this->addSizeVariation($variations, $variation, $category);
        }
    }

    public function addSharedProperties(SimpleXMLElement $node, $product, string $uniqueId = null, string $name = null, string $color = null): void
    {
        $category = $this->categories->get($product->category_id);

        $name ??= $product->translation;

        if (filled($color)) {
            $name .= ' ' . $color;
        }
        
        $skroutzPrice = $this->skroutzPrices[$product->id] ?? $product->net_value;

        $node->addChild('id', $uniqueId ?? $product->id);
        $node->addChild('category', e($this->breadcrumb($category->id)));
        $node->addChild('category_id', $category->id);
        $node->addChild('name', e($name));
        $node->addChild('price_with_vat', number_format($skroutzPrice, 2));
        $node->addChild('vat', number_format($product->vat * 100, 2));
        $node->addChild('instock', 'Y');
        $node->addChild('availability', e('Παράδοση σε 1 - 3 ημέρες'));
        $node->addChild('weight', $product->weight);
        $node->addChild('manufacturer', e($product->manufacturer));

        if (isset($product->description) && filled($product->description)) {
            $node->addChild('description', e($product->description));
        }

        if (filled($product->barcode)) {
            $node->addChild('barcode', e($product->barcode));
        }
        
        if (filled($color)) {
            $node->addChild('color', e($color));
        }
    }

    public function getImage($product): string
    {
        $image = $product->src;
        if ($product->has_watermark) {
            $conversions = json_decode($product->conversions, true);
            $image = $conversions['md']['src'] ?? $product->src;
        }

        return Storage::disk($product->disk)->url($image);
    }

    public function addSizeVariation(SimpleXMLElement $xml, $variation, $category): void
    {
        $node = $xml->addChild('variation');

        $skroutzPrice = $this->skroutzPrices[$variation->id] ?? $variation->net_value;
        
        $node->addChild('variationid', $variation->id);
        $node->addChild('link', e(route('products.show', ['el', $category->slug, $variation->slug])));
        $node->addChild('availability', e('Παράδοση 1 έως 3 ημέρες'));
        $node->addChild('manufacturer', e($variation->manufacturer));
        $node->addChild('price_with_vat', number_format($skroutzPrice, 2));
        $node->addChild('size', $variation->size);
        $node->addChild('quantity', (int)$variation->stock);

        if (filled($variation->mpn)) {
            $node->addChild('mpn', e($variation->mpn));
        }

        if (filled($variation->barcode)) {
            $node->addChild('barcode', e($variation->barcode));
        }
    }

    public function breadcrumb(int $category_id): string
    {
        $category = $this->categories->get($category_id);
        $breadcrumbs = [$category->translation];
        $parent_id = $category->parent_id;
        while ($parent_id) {
            $p = $this->categories->get($parent_id);
            array_unshift($breadcrumbs, $p->translation);
            $parent_id = $p->parent_id;
        }

        return implode(' > ', $breadcrumbs);
    }
}
