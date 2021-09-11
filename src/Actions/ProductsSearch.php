<?php

namespace Eshop\Actions;

use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsSearch
{
    public function handle(string $searchTerms, array $columns = ['id', 'category_id', 'slug']): Builder
    {
        return Product::select($columns)
            ->visible()
            ->with(['category' => fn($q) => $q->select('id', 'slug'), 'translation' => fn($q) => $q->select('translatable_id', 'translatable_type', 'translation')])
            ->whereHas('translations', fn($c) => $c->matchAgainst($searchTerms)->where('cluster', 'name'));
    }
}