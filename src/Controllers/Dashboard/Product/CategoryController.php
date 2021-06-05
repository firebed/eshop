<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('eshop::dashboard.category.index');
    }

    public function show(Category $category): View
    {
        return view('eshop::dashboard.category.index', compact('category'));
    }
}
