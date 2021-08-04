<?php

namespace Eshop\Controllers\Dashboard\Pos;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Illuminate\View\View;

class PosController extends Controller
{
    public function create(): View
    {
        $categories = Category::root()->with('translation', 'image')->get();
        return view('eshop::dashboard.pos.create', [
            'categories' => $categories
        ]);
    }

    public function store()
    {
    }
}
