<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Http\RedirectResponse;

class ProductVisibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage products');
    }

    public function __invoke(Product $product, AuditModel $audit): RedirectResponse
    {
        $product->visible = !$product->visible;
        $product->save();

        $audit->handle($product);
        
        return redirect()->to(productRoute($product));
    }
}