<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Actions\Product\StoreProduct;
use Eshop\Actions\Product\UpdateProduct;
use Eshop\Controllers\Dashboard\Controller;
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
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage products');
    }

    public function index(): Renderable
    {
        return $this->view('product.index');
    }

    public function create(): Renderable
    {
        return $this->view('product.create', [
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

    public function store(ProductRequest $request, StoreProduct $store, AuditModel $audit): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $product = $store->handle($request);
            $audit->handle($product);
            DB::commit();

            $this->showSuccessNotification(trans('eshop::notifications.created'));
            return redirect()->route('products.edit', $product);
        } catch (Throwable) {
            DB::rollBack();
            $request->flash();
            $this->showErrorNotification(trans('eshop::notifications.error'));
            return back();
        }
    }

    public function edit(Product $product): RedirectResponse|Renderable
    {
        if ($product->isVariant()) {
            return redirect()->route('variants.edit', $product);
        }

        return $this->view('product.edit', [
            'product'       => $product,
            'properties'    => $this->prepareProperties($product),
            'vats'          => Vat::all(),
            'units'         => Unit::all(),
            'variantTypes'  => $product->variantTypes()->with('translation')->orderBy('position')->get()->pluck('name', 'id')->map(fn($v, $k) => ['id' => $k, 'name' => $v])->values()->all(),
            'categories'    => Category::files()->with('translations', 'parent.translation')->get()->groupBy('parent_id'),
            'manufacturers' => Manufacturer::all(),
            'collections'   => Collection::all(),
        ]);
    }

    public function update(ProductRequest $request, Product $product, UpdateProduct $update, AuditModel $audit): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $update->handle($product, $request);
            $audit->handle($product);
            DB::commit();

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
        } catch (Throwable $e) {
            DB::rollBack();
            $request->flash();
            $this->showErrorNotification(trans('eshop::notifications.error') . ': ' . $e->getMessage());
        }

        return back();
    }

    public function destroy(Product $product, AuditModel $audit): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $product->delete();
            $audit->handle($product, true);
            DB::commit();

            $this->showSuccessNotification(trans('eshop::notifications.deleted'));
            return redirect()->route('products.index');
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
            return back();
        }
    }

    private function prepareProperties(Product $product): array
    {
        $choices = $product->properties
            ->groupBy('id')
            ->map(fn($g) => $g->pluck('pivot.category_choice_id')->all())
            ->all();

        return compact('choices');
    }
}
