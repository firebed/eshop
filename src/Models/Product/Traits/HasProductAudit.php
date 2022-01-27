<?php

namespace Eshop\Models\Product\Traits;

use Eshop\Models\Audit\Traits\HasAudits;

trait HasProductAudit
{
    use HasAudits;

    public function toAuditableArray(): array
    {
        $auditable = [
            'price'         => $this->price,
            'compare_price' => $this->compare_price,
            'discount'      => $this->discount,
            'vat'           => $this->vat,

            'sku'              => $this->sku,
            'mpn'              => $this->mpn,
            'barcode'          => $this->barcode,
            'is_physical'      => $this->is_physical,
            'weight'           => $this->weight,
            'stock'            => $this->stock,
            'visible'          => $this->getAttribute('visible'),
            'recent'           => $this->recent,
            'available'        => $this->available,
            'available_gt'     => $this->available_gt,
            'display_stock'    => $this->display_stock,
            'display_stock_lt' => $this->display_stock_lt,
            'location'         => $this->location,

            'has_variants'     => $this->has_variants,
            'variants_display' => $this->variants_display,
            'preview_variants' => $this->preview_variants,
            'slug'             => $this->slug,

            'category'     => $this->category?->name,
            'manufacturer' => $this->manufacturer?->name,
            'unit'         => $this->unit?->name
        ];

        $translations = [];
        foreach ($this->translations as $translation) {
            $translations[] = [
                'locale'      => $translation->locale,
                'cluster'     => $translation->cluster,
                'translation' => $translation->translation
            ];
        }
        $auditable['translations'] = $translations;

        $seos = [];
        foreach ($this->seos as $seo) {
            $seos[$seo->locale]['title'] = $seo->title;
            $seos[$seo->locale]['description'] = $seo->description;
        }
        $auditable['seo'] = $seos;

        if ($this->has_variants) {
            $auditable['variant_types'] = $this->variantTypes->sortBy('position')->pluck('name');
            
            $this->load('properties.translation', 'choices.translation');

            $properties = [];
            foreach ($this->properties as $property) {
                $properties[$property->name] = $this->choices
                    ->where('category_property_id', $property->id)
                    ->pluck('name')
                    ->all();
            }

            $auditable['properties'] = $properties;
        }

        if ($this->isVariant()) {
            $auditable['option_values'] = $this->options()->pluck('value', 'name')->all();
        }

        return $auditable;
    }

}