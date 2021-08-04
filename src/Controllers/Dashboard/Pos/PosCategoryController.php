<?php

namespace Eshop\Controllers\Dashboard\Pos;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class PosCategoryController extends Controller
{
    public function index(Request $request): Renderable
    {
        $parent = $request->filled('parent') ? Category::find($request->parent) : NULL;
        if ($parent && $parent->isFile()) {
            $products = $parent->products()->exceptVariants()->with('image', 'translation')->get('id');

            return view('eshop::dashboard.pos.partials.pos-products', compact('products'));
        }

        $categories = $parent ? $parent->children() : Category::root();

        return view('eshop::dashboard.pos.partials.pos-categories', [
            'categories' => $categories->with('translation', 'image')->get()
        ]);
    }

    public function show(Category $category): Renderable
    {
        $products = $parent->products()->exceptVariants()->with('image', 'translation')->get('id');

        return view('eshop::dashboard.pos.partials.pos-products', compact('products'));
    }
}
