<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Product\BarcodeGenerator;
use Eshop\Controllers\Dashboard\Controller;
use Illuminate\Http\Request;

class ProductBarcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage products');
    }

    public function __invoke(Request $request, BarcodeGenerator $generator): string
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'product_id'  => ['nullable', 'integer', 'exists:products,id'],
            'variant_id'  => ['nullable', 'integer', 'exists:products,id'],
        ]);

        return $generator->handle(...$data);
    }
}
