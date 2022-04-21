<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\ProductVariantOption;
use Eshop\Models\Product\VariantType;
use Eshop\Models\Seo\Seo;
use Eshop\Requests\Dashboard\Product\ProductCopyRequest;
use Eshop\Services\BarcodeService;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductCopyController extends Controller
{
    use WithNotifications;

    public function create(Product $product): Renderable
    {
        if (!session()->hasOldInput()) {
            session()->flashInput([
                'name'          => $product->name,
                'price'         => $product->price,
                'compare_price' => $product->compare_price,
                'sku'           => $product->sku,
                'slug'          => $product->slug,
                'seo.title'     => $product->seo->title,
            ]);
        }

        return $this->view('product-copy.create', compact('product'));
    }

    public function store(ProductCopyRequest $request, Product $product, BarcodeService $barcode): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $clone = $product->replicate(['net_value']);
            $clone->fill($request->validated());
            $clone->description = $product->description;
            $clone->barcode = $barcode->shouldFill() ? $barcode->generateForProduct($product->category_id) : null;
            $clone->save();

            $clone->seo()->save(new Seo([
                'title'       => $request->input('seo.title'),
                'description' => $product->seo->description,
                'locale'      => $product->seo->locale
            ]));

            $properties = $product->properties()->pluck('category_properties.id');
            if ($properties->isNotEmpty()) {
                $clone->properties()->attach($product->properties()->pluck('category_properties.id'));
            }

            $collections = $product->collections()->pluck('collections.id');
            if ($collections->isNotEmpty()) {
                $clone->collections()->attach($collections);
            }

            $channels = $product->channels()->pluck('channels.id');
            if ($channels->isNotEmpty()) {
                $clone->channels()->attach($channels);
            }

            if ($product->image && $product->image->fileExists()) {
                DB::afterCommit(static fn() => $clone->saveImage($product->image->path()));
            }

            if ($product->has_variants) {
                $types = $product->variantTypes()->with('translation')->get();
                foreach ($types as $item) {
                    $clone->variantTypes()->save(new VariantType([
                        'name'     => $item->name,
                        'slug'     => $item->slug,
                        'position' => $item->position
                    ]));
                }

                $types = $clone->variantTypes()->with('translation')->get();

                $variants = $product->variants()->with('seos', 'channels', 'image', 'options')->get();
                Collection::make($variants->pluck('options')->collapse()->pluck('pivot'))->load('translation');

                foreach ($variants as $key => $variant) {
                    $var = $variant->replicate(['parent_id', 'net_value']);
                    $var->slug = slugify([$clone->slug, $variant->options->pluck('pivot.name')]);
                    $var->sku = $clone->sku . '-' . ($key + 1);
                    $var->barcode = $barcode->shouldFill() ? $barcode->generateForVariant($clone) : null;
                    $clone->variants()->save($var);

                    if ($variant->image && $variant->image->fileExists()) {
                        DB::afterCommit(static fn() => $var->saveImage($variant->image->path()));
                    }

                    if ($variant->channels->isNotEmpty()) {
                        $var->channels()->attach($channels);
                    }

                    foreach ($variant->options as $option) {
                        $vt = $types->firstWhere('slug', $option->slug);
                        $var->variantOptions()->save(new ProductVariantOption([
                            'name'            => $option->pivot->name,
                            'variant_type_id' => $vt->id,
                            'slug'            => $option->pivot->slug,
                        ]));
                    }

                    $var->seo()->save(new Seo([
                        'title'  => $clone->seo->title . ' ' . $variant->options->pluck('pivot.name')->join(' '),
                        'locale' => app()->getLocale()
                    ]));
                }
            }
            
            DB::commit();

            $this->showSuccessNotification('Το προϊόν αντιγράφηκε με επιτυχία.');
            return redirect()->route('products.edit', $clone);
        } catch (Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()->withErrors(['db' => $e->getMessage()]);
        }
    }
}