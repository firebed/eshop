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
            ->with(['translations' => fn($q) => $q->where('cluster', 'name')])
            ->with(['category' => fn($q) => $q->select('id', 'slug'), 'translations' => fn($q) => $q->where('cluster', 'name')])
            ->whereHas('translations', fn($c) => $c->matchAgainst($searchTerms)->where('cluster', 'name'));
    }
}
