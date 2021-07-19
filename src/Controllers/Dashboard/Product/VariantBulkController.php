<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithVariantOptions;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Requests\Dashboard\Product\VariantBulkCreateRequest;
use Eshop\Requests\Dashboard\Product\VariantBulkUpdateRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class VariantBulkController extends Controller
{
    use WithNotifications,
        WithVariantOptions;

    public function create(Product $product): Renderable
    {
        return view('eshop::dashboard.variant.bulk-create', [
            'product'      => $product,
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')->all()
        ]);
    }

    public function store(VariantBulkCreateRequest $request, Product $product): RedirectResponse
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

    public function edit(Request $request, Product $product): Renderable
    {
        $ids = $request->query('ids');

        $request->session()->flashInput(['bulk_ids' => $ids]);

        return view('eshop::dashboard.variant.bulk-edit', [
            'product'      => $product,
            'properties'   => $request->query('properties', []),
            'variants'     => Product::whereKey($ids)->with('options')->get(),
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')->all()
        ]);
    }

    public function update(VariantBulkUpdateRequest $request): RedirectResponse
    {
        foreach($request->properties as $property) {
            $data = array_combine($request->input('bulk_ids'), $request->input("bulk_$property"));
            $distinct = array_unique($data);

            try {
                DB::transaction(function () use ($property, $data, $distinct) {
                    foreach ($distinct as $value) {
                        Product::whereKey(array_keys($data, $value))->update([
                            $property => $value
                        ]);
                    }
                });

                $count = count($data);
                if (count($request->input('properties', [])) === 1) {
                    $property = $request->input('properties')[0];
                    $this->showSuccessNotification(trans_choice("eshop::variant.notifications.{$property}_updated", $count, ['number' => $count]));
                } else {
                    $this->showSuccessNotification(trans("eshop::variant.notifications.saved_many"));
                }
            } catch (Throwable) {
                $this->showErrorNotification(trans('eshop::variant.notifications.error'));
            }
        }

        $request->flashOnly('bulk_ids');
        return back();
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
