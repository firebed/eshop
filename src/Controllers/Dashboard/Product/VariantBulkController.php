<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Actions\Product\Traits\SavesVariantOptions;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Requests\Dashboard\Product\VariantBulkCreateRequest;
use Eshop\Requests\Dashboard\Product\VariantBulkUpdateRequest;
use Eshop\Services\BarcodeService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class VariantBulkController extends Controller
{
    use WithNotifications, SavesVariantOptions;

    private AuditModel $audit;

    public function __construct(AuditModel $audit)
    {
        $this->middleware('can:Manage products');

        $this->audit = $audit;
    }

    public function create(Product $product): Renderable
    {
        return $this->view('variant.bulk-create', [
            'product'      => $product,
            'variantTypes' => VariantType::where('product_id', $product->id)->with('translation')->get()->pluck('name', 'id')->all()
        ]);
    }

    public function store(VariantBulkCreateRequest $request, Product $product, BarcodeService $barcode): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $variants = collect($request->input('variants', []));
            foreach ($variants as $input) {
                $variant = $product->replicate(['discount', 'slug', 'mpn', 'has_variants', 'variants_display', 'preview_variants', 'net_value', 'recent']);

                $variant->fill([
                    'price'   => $input['price'],
                    'stock'   => $input['stock'],
                    'sku'     => $input['sku'],
                    'barcode' => $input['barcode'],
                    'slug'    => $product->slug . "-" . slugify($input['options'])
                ]);

                if (blank($variant->barcode) && $barcode->shouldFill()) {
                    $variant->barcode = $barcode->generateForVariant($product);
                }

                $product->variants()->save($variant);

                $this->saveVariantOptions($variant, $input['options']);

                $options = implode(' ', $input['options']);

                $variant->seo()->create([
                    'locale' => app()->getLocale(),
                    'title'  => $product->name . ' ' . $options
                ]);

                $this->audit->handle($variant);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification(trans('eshop::variant.notifications.error') . ': ' . $e->getMessage());
            $request->flash();
            return back();
        }

        $count = count($request->variants);
        $this->showSuccessNotification(trans_choice('eshop::variant.notifications.created_many', $count, ["number" => $count]));
        return redirect()->route('products.variants.index', $product);
    }

    public function edit(Request $request, Product $product): RedirectResponse|Renderable
    {
        $ids = $request->query('ids');
        $variants = empty($ids)
            ? $product->variants->load('options')
            : Product::whereKey($ids)->with('options')->get();

        $variants = $variants->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE);

        $request->session()->flashInput(['bulk_ids' => $ids ?? []]);

        return $this->view('variant.bulk-edit', [
            'product'      => $product,
            'properties'   => $request->query('properties', ['price', 'compare_price', 'discount', 'sku', 'stock', 'weight']),
            'variants'     => $variants,
            'variantTypes' => VariantType::where('product_id', $product->id)->pluck('name', 'id')->all()
        ]);
    }

    public function update(VariantBulkUpdateRequest $request): RedirectResponse
    {
        foreach ($request->properties as $property) {
            $data = array_combine($request->input('bulk_ids'), $request->input("bulk_$property"));
            $distinct = array_unique($data);

            DB::beginTransaction();
            try {
                foreach ($distinct as $value) {
                    $keys = array_keys($data, $value);

                    Product::whereKey($keys)->update([
                        $property => $value
                    ]);
                }
                DB::commit();

                $count = count($data);
                if (count($request->input('properties', [])) === 1) {
                    $property = $request->input('properties')[0];
                    $this->showSuccessNotification(trans_choice("eshop::variant.notifications.{$property}_updated", $count, ['number' => $count]));
                } else {
                    $this->showSuccessNotification(trans("eshop::variant.notifications.saved_many"));
                }
            } catch (Throwable) {
                DB::rollBack();
                $this->showErrorNotification(trans('eshop::variant.notifications.error'));
            }
        }

        DB::transaction(function () use ($request) {
            $models = Product::with('category', 'translations', 'parent.translations', 'options.translation', 'manufacturer', 'unit', 'seos')
                ->whereKey($request->input('bulk_ids'))
                ->get();

            (new Collection($models->pluck('options')->flatten()->pluck('pivot')))->load('translation');
            
            foreach ($models as $model) {
                $this->audit->handle($model);
            }
        });

        $request->flashOnly('bulk_ids');
        return back();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'exists:products,id'],
            'ids.*' => ['required', 'integer']
        ]);

        $variants = Product::findMany($request->input('ids'));
        DB::transaction(function () use ($variants) {
            foreach ($variants as $variant) {
                $variant->delete();
                $this->audit->handle($variant, true);
            }
        });

        $count = $variants->count();
        $this->showSuccessNotification(trans_choice('eshop::variant.notifications.deleted_many', $count, ["number" => $count]));

        return redirect()->route('products.variants.index', $variants->first()->parent_id);
    }
}
