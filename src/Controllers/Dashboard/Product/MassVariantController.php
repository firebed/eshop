<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithVariantOptions;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Requests\Dashboard\Product\MassVariantRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MassVariantController extends Controller
{
    use WithNotifications,
        WithVariantOptions;

    public function create(Product $product): Renderable
    {
        return view('eshop::dashboard.variant.mass-create', [
            'product'      => $product,
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')->all()
        ]);
    }

    public function store(MassVariantRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $product) {
                collect($request->input('variants', []))
                    ->each(function ($input) use ($product) {
                        $variant = $product->replicate(['slug', 'variants_display', 'preview_variants', 'net_value']);

                        $variant->fill([
                            'price'   => $input['price'],
                            'stock'   => $input['stock'],
                            'sku'     => $input['sku'],
                            'barcode' => $input['barcode'],
                            'slug'    => $product->slug . '-' . slugify($input['options'])
                        ]);

                        $product->variants()->save($variant);

                        $this->saveVariantOptions($variant, $input['options']);

                        $variant->seo()->create([
                            'locale' => app()->getLocale(),
                            'title'  => $variant->trademark
                        ]);
                    });
            });
        } catch (Throwable) {
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
            $request->flash();
            return back();
        }

        $count = count($request->variants);
        $this->showSuccessNotification(trans_choice('eshop::variant.notifications.created_many', $count, ["number" => $count]));
        return redirect()->route('products.variants.index', $product);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'exists:products,id'],
            'ids.*' => ['required', 'integer']
        ]);

        $variants = Product::findMany($request->input('ids'))->each->delete();

        $count = $variants->count();
        $this->showSuccessNotification(trans_choice('eshop::variant.notifications.deleted_many', $count, ["number" => $count]));

        return redirect()->route('products.variants.index', $variants->first()->parent_id);
    }
}
