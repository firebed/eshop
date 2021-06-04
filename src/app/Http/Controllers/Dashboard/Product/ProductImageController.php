<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductImageController extends Controller
{
    public function index(int $productId): Renderable
    {
        return view('dashboard.product-images.index', compact('productId'));
    }
//    public function update(Request $request, Product $product): JsonResponse
//    {
//        if ($request->filled('url') || $request->hasFile('icon')) {
//            if ($product->image) {
//                $product->image->delete();
//            }
//            $image = $product->saveImage($request->url ?? $request->file('icon'));
//            return response()->json([
//                'src' => $image->url('sm')
//            ]);
//        }
//    }
}
