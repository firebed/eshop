<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Models\Product\Category;
use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('eshop::dashboard.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return View
     */
    public function show($category): View
    {
        dd($category, Category::find($category));
        return view('eshop::dashboard.category.index', compact('category'));
    }
}
