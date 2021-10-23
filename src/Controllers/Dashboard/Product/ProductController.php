<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithImage;
use Eshop\Controllers\Dashboard\Product\Traits\WithProductProperties;
use Eshop\Controllers\Dashboard\Product\Traits\WithVariantTypes;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Collection;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\Vat;
use Eshop\Requests\Dashboard\Product\ProductRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{
    use WithVariantTypes,
        WithProductProperties,
        WithNotifications,
        WithImage;

    public function __construct()
    {
        $this->middleware('can:Manage products');
    }

    public function index(): Renderable
    {
        return view('eshop::dashboard.product.index');
    }

    public function create(): Renderable
    {
        return view('eshop::dashboard.product.create', [
            'vats'          => Vat::all(),
            'units'         => Unit::all(),
            'variantTypes'  => collect([]),
            'categories'    => Category::files()->with('translations', 'parent.translation')->get()->groupBy('parent_id'),
            'manufacturers' => Manufacturer::all(),
            'collections'   => Collection::all(),
            'choices'       => [],
            'values'        => [],
            'props'         => []
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            $product = new Product();
            $product->fill($request->only($product->getFillable()));

            DB::transaction(function () use ($product, $request) {
                $product->save();

                $product->seo()->create($request->input('seo'));

                if ($request->filled('variantTypes')) {
                    $this->saveVariantTypes($product, $request->input('variantTypes'));
                }

                if ($request->filled('properties')) {
                    $this->saveProperties($product, $request->input('properties'));
                }

                $product->collections()->sync($request->input('collections', []));

                if ($request->hasFile('image')) {
                    $product->saveImage($request->file('image'));
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.created'));
        } catch (Throwable) {
            $request->flash();
            $this->showErrorNotification(trans('eshop::notifications.error'));
            return back();
        }

        return redirect()->route('products.edit', $product);
    }

    public function edit(Product $product): RedirectResponse|Renderable
    {
        if ($product->isVariant()) {
            return redirect()->route('variants.edit', $product);
        }
        
        return view('eshop::dashboard.product.edit', [
            'product'       => $product,
            'properties'    => $this->prepareProperties($product),
            'vats'          => Vat::all(),
            'units'         => Unit::all(),
            'variantTypes'  => $product->variantTypes()->orderBy('id')->pluck('name', 'id')->map(fn($v, $k) => ['id' => $k, 'name' => $v])->values()->all(),
            'categories'    => Category::files()->with('translations', 'parent.translation')->get()->groupBy('parent_id'),
            'manufacturers' => Manufacturer::all(),
            'collections'   => Collection::all(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::transaction(function () use ($product, $request) {
                if ($product->has_variants) {
                    if ($product->category_id !== $request->input('category_id')) {
                        $product->variants()->update(['category_id' => $request->input('category_id')]);
                    }
                    
                    if ($product->manufacturer_id !== $request->input('manufacturer_id')) {
                        $product->variants()->update(['manufacturer_id' => $request->input('manufacturer_id')]);
                    }

                    if ($product->unit_id !== $request->input('unit_id')) {
                        $product->variants()->update(['unit_id' => $request->input('unit_id')]);
                    }
                }
                                
                $product->update($request->only($product->getFillable()));

                $product->seo()->updateOrCreate([], $request->input('seo'));

                $this->syncVariantTypes($product, $request->input('variantTypes', []));

                $this->saveProperties($product, $request->input('properties', []));

                $product->collections()->sync($request->input('collections', []));

                if ($request->hasFile('image')) {
                    $this->replaceImage($product, $request->file('image'));
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
        } catch (Throwable $e) {
            $request->flash();
            $this->showErrorNotification(trans('eshop::notifications.error') . ': ' . $e->getMessage());
        }

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            DB::transaction(function () use ($product) {
                $product->variants->each->delete();
                $product->delete();
            });

            $this->showSuccessNotification(trans('eshop::notifications.deleted'));
            return redirect()->route('products.index');
        } catch (Throwable $e) {
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
            return back();
        }
    }
}
