<?php

namespace Eshop\Services\Skroutz\Traits;

use Eshop\Models\Product\Channel;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

trait LoadsProducts
{
    protected Collection $categories;

    private string     $locale;
    private Collection $parents;
    private Collection $descriptions;
    private Collection $products;
    private Collection $inSkroutz;

    public function handle(): ?SimpleXMLElement
    {
        if ($this->loadData()) {
            return $this->createXml();
        }

        return null;
    }

    protected function loadData(): bool
    {
        $skroutz = Channel::firstWhere('name', 'Skroutz');
        if (!$skroutz) {
            return false;
        }

        $this->inSkroutz = DB::table('channel_product')
            ->where('channel_id', $skroutz->id)
            ->pluck('channel_id', 'product_id');

        $this->locale = config('app.locale');

        $this->categories = $this->getCategories();

        $this->parents = $this->getParents();
        
        $this->descriptions = $this->getProductDescriptions();

        $this->products = $this->getProducts();

        $this->variantTypes = $this->getVariantTypes();

        $this->productVariantTypes = $this->getProductVariantTypes();

        return true;
    }

    protected function getCategories(): Collection
    {
        return DB::table('categories')
            ->where('visible', true)
            ->join('translations', function (JoinClause $q) {
                $q->on('translations.translatable_id', '=', 'categories.id');
                $q->where('translations.locale', $this->locale);
                $q->where('translations.translatable_type', 'category');
                $q->where('translations.cluster', 'name');
            })
            ->get(['categories.id', 'parent_id', 'slug', 'translations.translation'])
            ->keyBy('id');
    }

    protected function getParents(): Collection
    {
        return DB::table('products')
            ->where('visible', true)
            ->where('has_variants', true)
            ->whereIntegerInRaw('category_id', $this->categories->keys())
            ->whereNull('deleted_at')
            ->join('translations', function (JoinClause $q) {
                $q->on('translations.translatable_id', '=', 'products.id');
                $q->where('translations.locale', $this->locale);
                $q->where('translations.translatable_type', 'product');
                $q->where('translations.cluster', 'name');
            })
            ->join('images', function (JoinClause $j) {
                $j->on('images.imageable_id', '=', 'products.id');
                $j->where('images.imageable_type', 'product');
                $j->whereNull('images.collection');
            })
            ->get(['products.id', 'images.disk', 'images.src', 'images.conversions', 'translations.translation', 'category_id', 'available', 'slug', 'has_watermark'])
            ->keyBy('id');
    }

    protected function getProductDescriptions(): Collection
    {
        return DB::table('seo')
            ->whereIn('seo_id', $this->parents->keys())
            ->where('seo_type', 'product')
            ->pluck('description', 'id');
    }

    protected function getProducts(): Collection
    {
        return DB::table('products')
            ->where('visible', true)
            ->where('has_variants', false)
            ->whereNull('deleted_at')
            ->whereIntegerInRaw('category_id', $this->categories->keys())
            ->whereIntegerInRaw('products.id', $this->inSkroutz->keys())
            ->whereNull('deleted_at')
            ->leftJoin('translations', function (JoinClause $q) {
                $q->on('translations.translatable_id', '=', 'products.id');
                $q->where('translations.locale', $this->locale);
                $q->where('translations.translatable_type', 'product');
                $q->where('translations.cluster', 'name');
            })
            ->leftJoin('images', function (JoinClause $j) {
                $j->on('images.imageable_id', '=', 'products.id');
                $j->where('images.imageable_type', 'product');
                $j->whereNull('images.collection');
            })
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->get(['products.id', 'images.disk', 'images.src', 'images.conversions', 'manufacturers.name as manufacturer', 'translations.translation', 'parent_id', 'category_id', 'manufacturer_id', 'mpn', 'vat', 'weight', 'net_value', 'stock', 'available', 'available_gt', 'has_watermark', 'sku', 'products.slug', 'has_variants', 'barcode'])
            ->filter(fn($p) => $p->parent_id === null || $this->parents->has($p->parent_id))
            ->filter(fn($p) => $this->canPurchase($p, $this->parents[$p->parent_id] ?? null))
            ->filter(fn($p) => $p->src !== null)
            ->map(function($p) {
                if (isset($this->descriptions[$p->parent_id])) {
                    $p->description = strip_tags($this->descriptions[$p->parent_id]);
                }
                
                return $this->mapProduct($p);
            })
            ->filter()
            ->keyBy('id')
            ->sortKeys();
    }

    protected function mapProduct($product): mixed
    {
        return $product;
    }

    protected function getVariantTypes(): Collection
    {
        return DB::table('variant_types')
            ->whereIntegerInRaw('product_id', $this->parents->keys())
            ->get(['id', 'product_id', 'slug'])
            ->groupBy('product_id')
            ->map(fn($c) => $c->keyBy('slug'));
    }

    protected function getProductVariantTypes(): Collection
    {
        return DB::table('product_variant_type')
            ->join('translations', function (JoinClause $q) {
                $q->on('translations.translatable_id', '=', 'product_variant_type.id');
                $q->where('translations.locale', $this->locale);
                $q->where('translations.translatable_type', 'product_variant_type');
                $q->where('translations.cluster', 'name');
            })
            ->whereIntegerInRaw('product_id', $this->products->keys())
            ->get(['product_id', 'variant_type_id', 'translation'])
            ->groupBy('product_id')
            ->map(fn($g) => $g->keyBy('variant_type_id'));
    }
}