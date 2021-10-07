<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VariantBulkImageController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage products');
    }
    
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'exists:products,id'],
            'ids.*' => ['required', 'integer'],
            'image' => ['required', 'image']
        ]);

        $variants = Product::whereKey($request->input('ids'))->with('image')->get();
        foreach ($variants as $variant) {
            if ($variant->image) {
                $variant->image->delete();
            }

            $variant->saveImage($request->file('image'));
        }

        $this->showSuccessNotification(trans_choice('eshop::variant.notifications.images_updated', $variants->count(), ['number' => $variants->count()]));
        return back();
    }
}
