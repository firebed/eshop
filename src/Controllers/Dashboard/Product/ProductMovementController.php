<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Product\ProductMovements;
use Eshop\Actions\Product\ProductMovementsSummary;
use Eshop\Actions\Product\VariantMovementSummary;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ProductMovementController extends Controller
{
    private ProductMovements        $productMovements;
    private ProductMovementsSummary $productSummary;
    private VariantMovementSummary  $variantsSummary;

    public function __construct(ProductMovements $productMovementsAction, ProductMovementsSummary $productSummary, VariantMovementSummary $variantMovementSummary)
    {
        $this->middleware('can:View movements');

        $this->productMovements = $productMovementsAction;
        $this->productSummary = $productSummary;
        $this->variantsSummary = $variantMovementSummary;
    }

    public function __invoke(Request $request, Product $product): Renderable
    {
        if ($product->has_variants) {
            $variants = $this->variantsSummary->handle($product);
            return $this->view('product-movements.variants-index', compact('product', 'variants'));
        }

        $movements = $this->productMovements->handle($product)->paginate(25);

        $totals = $this->productSummary->handle($product);

        return $this->view('product-movements.index', compact('product', 'totals', 'movements'));
    }
}
