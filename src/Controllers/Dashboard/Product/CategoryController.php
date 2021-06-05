<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Models\Product\Category;
use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('eshop::dashboard.category.index');
    }

    public function show($category): View
    {
        dd($category, Category::find($category));
        return view('eshop::dashboard.category.index', compact('category'));
    }
}
