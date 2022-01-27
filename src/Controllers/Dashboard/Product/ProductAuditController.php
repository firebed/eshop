<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Audit\ModelAudit;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;

class ProductAuditController extends Controller
{
    public function index(Product $product): Renderable
    {
        $audits = $product->audits()->with('user')->latest()->paginate();
        
        return $this->view('product-audits.index', compact('product', 'audits'));
    }

    public function show(ModelAudit $audit): JsonResponse
    {
        $product = $audit->auditable;

        $view = $this->view('product-audits.show', compact('product', 'audit'))->render();
        return response()->json($view);
    }
}
