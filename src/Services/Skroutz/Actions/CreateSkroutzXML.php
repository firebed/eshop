<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\SkroutzXML;
use Eshop\Services\Skroutz\Traits\LoadsProducts;
use Illuminate\Support\Collection;
use SimpleXMLElement;

class CreateSkroutzXML
{
    use LoadsProducts;

    public function createXml(): ?SimpleXMLElement
    {
        $xml = new SkroutzXML($this->categories);

        $products = $this->products->groupBy('parent_id');

        foreach ($products as $parent_id => $group) {
            if (blank($parent_id)) {
                foreach ($group as $product) {
                    $this->addSimpleProduct($xml, $product);
                }
                continue;
            }

            $parent = $this->parents[$parent_id];

            // Get the available variant types
            $types = $this->variantTypes->get($parent_id);

            // If the product has color and size variation together then we
            // will add each color as a different product using a combination
            // of the variant's parent id and the color name as the unique id.
            // Then we will add all the possible size variations having the
            // specified color using the variant's id as the unique id.
            if ($types->has('xrwma') && $types->has('megethos')) {
                $colorVariant = $types->get('xrwma');
                $sizeVariant = $types->get('megethos');

                $group = $group->each(function ($p) use ($colorVariant, $sizeVariant) {
                    $p->color_slug = $this->productVariantTypes[$p->id][$colorVariant->id]->slug;
                    $p->color = $this->productVariantTypes[$p->id][$colorVariant->id]->translation;
                    $p->size = str_replace(',', '.', $this->productVariantTypes[$p->id][$sizeVariant->id]->translation);
                })->groupBy('color_slug');

                $category = $this->categories->get($parent->category_id);
                foreach ($group as $color => $sizeVariations) {
                    $uniqueId = $parent->id . '-' . $color;
                    $link = route('products.show', [$this->locale, $category->slug, $parent->slug, 'options' => $color]);
                    $this->addProductWithSizeVariations($xml, $parent, $uniqueId, name: $parent->translation, link: $link, color: $color, sizeVariations: $sizeVariations);
                }

                continue;
            }

            // If a product has only color variations then we will add each color
            // as a different product using the variation's id ad the unique id.
            if ($types->has('xrwma')) {
                $colorVariant = $types->get('xrwma');
                foreach ($group as $variant) {
                    $color = $this->productVariantTypes[$variant->id][$colorVariant->id]->translation;

                    $this->addSimpleProduct($xml, $variant, name: $parent->translation . ' ' . $variant->translation, color: $color);
                }

                continue;
            }

            // If a product has only size variations then we will add the
            // parent product using the parent's id as the unique id, and
            // we will include each variation inside the variations tag
            // having the variant's id as size variation unique id.
            if ($types->has('megethos')) {
                $sizeVariant = $types->get('megethos');

                $sizeVariations = collect();
                foreach ($group as $variant) {
                    $size = $this->productVariantTypes[$variant->id][$sizeVariant->id]->translation;
                    $variant->size = str_replace(',', '.', $size);
                    $sizeVariations->push($variant);
                }

                $this->addProductWithSizeVariations($xml, $parent, sizeVariations: $sizeVariations);
            }
        }

        return $xml->getXML();
    }

    protected function addSimpleProduct(SkroutzXML $xml, $product, string $uniqueId = null, string $name = null, string $link = null, string $color = null): void
    {
        $xml->addSimpleProduct($product, $uniqueId, $name, $link, $color);
    }

    protected function addProductWithSizeVariations(SkroutzXML $xml, $product, string $uniqueId = null, string $name = null, string $link = null, string $color = null, Collection $sizeVariations = null): void
    {
        $xml->addProductWithSizeVariations($product, $uniqueId, $name, $link, $color, $sizeVariations);
    }

    protected function canPurchase($product, $parent = null): bool
    {
        if ($parent !== null && !$parent->available) {
            return false;
        }

        return $product->available && ($product->available_gt === null || ($product->stock - 1 >= $product->available_gt));
    }
}
