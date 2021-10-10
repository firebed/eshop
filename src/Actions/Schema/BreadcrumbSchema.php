<?php

namespace Eshop\Actions\Schema;

use Eshop\Models\Media\Image;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class BreadcrumbSchema
{
    public function handle(Category $category, Product $product = null): string
    {
        $category->loadMissing('translation');
        $items = [$this->item(categoryRoute($category), $category->seo->title ?? $category->name, $category->image)];

        $parent = $category->parent;
        while ($parent) {
            $parent->load('seo', 'translation');
            array_unshift($items, $this->item(categoryRoute($parent), $parent->seo->title ?? $parent->name, $parent->image));
            $parent = $parent->parent;
        }

        if ($product !== null) {
            $product->loadMissing('translations');
            
            if ($product->isVariant()) {
                $product->loadMissing('parent.translations');
                $parent = $product->parent;
                $items[] = $this->item(productRoute($parent, $category), $parent->seo->title, $parent->image);
            }
            
            $items[] = $this->item(productRoute($product, $category), $product->seo->title ?? $product->name, $product->image);
        }

        foreach ($items as $i => $iValue) {
            $items[$i]['position'] = $i + 1;
        }
        
        return json_encode([
            "@context"        => "https://schema.org",
            "@type"           => "BreadcrumbList",
            "itemListElement" => $items
        ]);
    }

    private function item(string $url, string $name, Image $image = null): array
    {
        $item = [
            "@type"    => "ListItem",
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