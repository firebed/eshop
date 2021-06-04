<?php

namespace Ecommerce\Controllers\Dashboard\Product;

use App\Models\Product\Category;
use Ecommerce\Controllers\Controller;
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
        return view('dashboard.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return View
     */
    public function show(Category $category): View
    {
        return view('dashboard.category.index', compact('category'));
    }
}
