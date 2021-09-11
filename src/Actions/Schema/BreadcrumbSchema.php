<?php

namespace Eshop\Actions\Schema;

use Eshop\Models\Media\Image;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class BreadcrumbSchema
{
    public function handle(Category $category, Product $product = null, Product $variant = null): string
    {
        $category->loadMissing('translation');
        $items = [$this->item(categoryRoute($category), $category->seo->title ?? $category->name, $category->image)];

        $parent = $category->parent;
        while ($parent) {
            $parent->load('seo', 'translation');
            array_unshift($items, $this->item(categoryRoute($parent), $parent->seo->title ?? $parent->name, $parent->image));
            $parent = $parent->parent;
        }

        foreach ($items as $i => $iValue) {
            $items[$i]['position'] = $i + 1;
        }

        if ($product !== null) {
            $product->loadMissing('translation');
            $items[] = $this->item(productRoute($product, $category), $product->seo->title ?? $product->name, $product->image, count($items) + 1);
        }

        if ($variant !== null) {
            $variant->loadMissing('translation');
            $items[] = $this->item(variantRoute($variant, $product, $category), $variant->seo->title ?? $variant->option_values, $variant->image, count($items) + 1);
        }

        return json_encode([
            "@context"        => "https://schema.org",
            "@type"           => "BreadcrumbList",
            "itemListElement" => $items
        ]);
    }

    private function item(string $url, string $name, Image $image = null, int $position = 0): array
    {
        $item = [
            "@type"    => "ListItem",
            "position" => $position,
            "item"     => [
                "@id"  => $url,
                'name' => $name,
            ]
        ];

        if ($image !== null) {
            $item['item']['image'] = $image->url();
        }

        return $item;
    }
}