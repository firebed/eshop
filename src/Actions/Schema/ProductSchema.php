<?php

namespace Eshop\Actions\Schema;

use Eshop\Models\Product\Product;

class ProductSchema
{
    public function toArray(Product $product): array
    {
        if ($product->has_variants) {
            $product->loadMissing('variants.image', 'variants.seo', 'variants.options');
        }

        if ($product->seo === null) {
            $product->loadMissing('variants.parent', 'translations', 'variants.translations');
        }

        $sData = [
            "@context" => "https://schema.org/",
            "@type"    => "Product",
            "name"     => $product->seo->title ?? $product->trademark,
            "sku"      => $product->sku,
            //            "aggregateRating" => [
            //                "@type"       => "AggregateRating",
            //                "ratingValue" => 0,
            //                "reviewCount" => 0
            //            ]
        ];

        if ($product->seo?->description !== null) {
            $sData["description"] = $product->seo->description;
        }

        if ($src = $product->image?->url('sm')) {
            $sData["image"] = $src;
        }

        if (!empty($product->mpn)) {
            $sData['mpn'] = $product->mpn;
        }

        if ($product->has_variants) {
            $sData['offers'] = $this->aggregateOffer($product);
            $sData['offers']['url'] = productRoute($product);

            if ($product->variants->isNotEmpty()) {
                $models = [];
                foreach ($product->variants as $variant) {
                    $model = $this->model($variant, $product);
                    $model["offers"]['url'] = variantRoute($variant, $product, $product->category);
                    $models[] = $model;
                }
                $sData['model'] = $models;
            }
        } else {
            $sData['offers'] = $this->offer($product);
            $sData['offers']['url'] = productRoute($product);
        }

        return $sData;
    }

    public function handle(Product $product): string
    {
        return json_encode($this->toArray($product));
    }

    private function model(Product $product, Product $parent): array
    {
        $model = [
            "@type"  => "ProductModel",
            "name"   => $product->seo->title ?? $product->trademark,
            "sku"    => $product->sku,
            "offers" => $this->offer($product),
            //            "aggregateRating" => [
            //                "@type"       => "AggregateRating",
            //                "ratingValue" => 0,
            //                "reviewCount" => 0
            //            ]
        ];

        if (!empty($product->mpn)) {
            $model['mpn'] = $product->mpn;
        }

        if ($src = $product->image?->url('sm')) {
            $model['image'] = $src;
        }
        
        $description = $product->seo->description ?? $parent->seo?->description;
        if ($description !== null) {
            $model["description"] = $description;
        }

        foreach ($product->options as $option) {
            if ($option->name === "Μέγεθος") {
                $model['size'] = $option->pivot->value;
            } elseif ($option->name === "Χρώμα") {
                $model['color'] = $option->pivot->value;
            } elseif ($option->name === "Υλικό") {
                $model['material'] = $option->pivot->value;
            }
        }

        return $model;
    }

    private function offer(Product $product): array
    {
        return [
            "@type"           => "Offer",
            "price"           => $product->net_value,
            "priceCurrency"   => config("eshop.currency"),
            "availability"    => $product->canBeBought() ? "InStock" : "OutOfStock",
            "priceValidUntil" => today()->addMonth()->format('Y-m-d'),
        ];
    }

    private function aggregateOffer(Product $product): array
    {
        $lowPrice = $product->variants->min('net_value');
        $highPrice = $product->variants->max('net_value');

        if ($lowPrice === $highPrice) {
            return $this->offer($product);
        }

        return [
            "@type"           => "AggregateOffer",
            "lowPrice"        => $lowPrice,
            "highPrice"       => $highPrice,
            "priceCurrency"   => config("eshop.currency"),
            "availability"    => $product->canBeBought() ? "InStock" : "OutOfStock",
            "url"             => productRoute($product),
            "priceValidUntil" => today()->addMonth()->format('Y-m-d'),
        ];
    }
}