<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Actions\Product\StoreVariant;
use Eshop\Actions\Product\UpdateProduct;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\VariantType;
use Eshop\Models\Product\Vat;
use Eshop\Requests\Dashboard\Product\VariantRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class VariantController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage products');
    }

    public function index(Product $product): Renderable
    {
        return $this->view('variant.index', compact('product'));
    }

    public function create(Product $product): Renderable
    {
        return $this->view('variant.create', [
            'product'      => $product,
            'vats'         => Vat::all(),
            'units'        => Unit::all(),
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')
        ]);
    }

    public function store(VariantRequest $request, Product $product, StoreVariant $store, AuditModel $audit): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $variant = $store->handle($request, $product);
            $audit->handle($variant);
            DB::commit();

            $this->showSuccessNotification(trans('eshop::variant.notifications.created'));
            return redirect()->route('variants.edit', $variant);
        } catch (Throwable) {
            DB::rollBack();
            $request->flash();
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
            return back();
        }
    }

    public function edit(Product $variant): Renderable
    {
        (new Collection($variant->options->pluck('pivot')))->load('translation');

        return $this->view('variant.edit', [
            'product'      => $variant->parent,
            'variant'      => $variant,
            'vats'         => Vat::all(),
            'units'        => Unit::all(),
            'variantTypes' => VariantType::where('product_id', $variant->parent->id)->with('translation')->orderBy('position')->get()->pluck('name', 'id'),
            'options'      => $variant->options->pluck('pivot.name', 'id')
        ]);
    }

    public function update(VariantRequest $request, Product $variant, UpdateProduct $update, AuditModel $audit): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $update->handle($variant, $request);
            $audit->handle($variant);
            DB::commit();

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
        } catch (Throwable $e) {
            throw $e;
            DB::rollBack();
            $request->flash();
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
        }

        return back();
    }

    public function destroy(Product $variant, AuditModel $audit): RedirectResponse
    {
        DB::transaction(function () use ($variant, $audit) {
            $variant->delete();
            $audit->handle($variant, true);

            $this->showSuccessNotification(trans('eshop::variant.notifications.deleted'));
        });

        return redirect()->route('products.variants.index', $variant->parent_id);
    }
}
