<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithImage;
use Eshop\Controllers\Dashboard\Product\Traits\WithVariantOptions;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\VariantType;
use Eshop\Models\Product\Vat;
use Eshop\Requests\Dashboard\Product\VariantRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class VariantController extends Controller
{
    use WithNotifications,
        WithVariantOptions,
        WithImage;

    public function __construct()
    {
        $this->middleware('can:Manage products');
    }
    
    public function index(Product $product): Renderable
    {
        return view('eshop::dashboard.variant.index', compact('product'));
    }

    public function create(Product $product): Renderable
    {
        return view('eshop::dashboard.variant.create', [
            'product'      => $product,
            'vats'         => Vat::all(),
            'units'        => Unit::all(),
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')
        ]);
    }

    public function store(VariantRequest $request, Product $product): RedirectResponse
    {
        try {
            $variant = $product->replicate(['slug', 'has_variants', 'variants_display', 'preview_variants', 'net_value']);

            DB::transaction(function () use ($request, $product, $variant) {
                $variant->fill($request->only($variant->getFillable()));

                $product->variants()->save($variant);

                $variant->seo()->create($request->input('seo'));

                $this->saveVariantOptions($variant, $request->input('options'));

                if ($request->hasFile('image')) {
                    $variant->saveImage($request->file('image'));
                }
            });

            $this->showSuccessNotification(trans('eshop::variant.notifications.created'));
        } catch (Throwable) {
            $request->flash();
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
            return back();
        }

        return redirect()->route('variants.edit', $variant);
    }

    public function edit(Product $variant): Renderable
    {
        return view('eshop::dashboard.variant.edit', [
            'product'      => $variant->parent,
            'variant'      => $variant,
            'vats'         => Vat::all(),
            'units'        => Unit::all(),
            'variantTypes' => VariantType::where('product_id', $variant->parent_id)->orderBy('id')->pluck('name', 'id'),
            'options'      => $variant->options->pluck('pivot.value', 'id')
        ]);
    }

    public function update(VariantRequest $request, Product $variant): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $variant) {
                $variant->update($request->only($variant->getFillable()));

                $variant->seo()->updateOrCreate([], $request->input('seo'));

                $variant->options()->sync([]);
                $this->saveVariantOptions($variant, $request->input('options'));

                if ($request->hasFile('image')) {
                    $this->replaceImage($variant, $request->file('image'));
                }

                $this->showSuccessNotification(trans('eshop::variant.notifications.saved'));
            });
        } catch (Throwable) {
            $request->flash();
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
        }

        return back();
    }

    public function destroy(Product $variant): RedirectResponse
    {
        $variant->delete();

        $this->showSuccessNotification(trans('eshop::variant.notifications.deleted'));

        return redirect()->route('products.variants.index', $variant->parent_id);
    }
}
