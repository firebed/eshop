<?php

namespace Eshop\Commands;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScoutIndexCommand extends Command
{
    protected $signature = 'scout:refresh';

    protected $description = 'Inserts all models into index table';

    public function handle(): void
    {
        DB::table('scout_index')->truncate();

        Category::visible()
            ->with('translations')
            ->get()
            ->searchable();

        Product::visible()
            ->with('translations', 'parent.translations', 'category.translations', 'manufacturer')
            ->get()
            ->searchable();
    }
}