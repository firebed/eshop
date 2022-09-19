<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

/**
 * Class ProductVariantsButtons
 * @package Eshop\Livewire\Customer\Product
 *
 * @property Collection variants
 */
class ProductVariantsButtons extends Component
{
    use ControlsOrder,
        SendsNotifications;

    public ?Product $product  = null;
    public string   $quantity = "1";
    public string   $options  = '';
    public array    $filters  = [];
    public int      $variantId;

    public array  $available   = [];
    protected     $queryString = ['options' => ['except' => '']];
    public string $color       = '';
    public string $size        = '';

    public function mount(): void
    {
        if (filled($this->options)) {
            $uniqueOptions = $this->uniqueVariantOptions->collapse();

            $userSelected = explode('-', $this->options);
            foreach ($userSelected as $f) {
                $option = $uniqueOptions->firstWhere('pivot.slug', $f);
                if ($option !== null) {
                    $this->filters[$option->pivot->variant_type_id] = $f;

                    if ($option->slug === 'xrwma') {
                        $this->color = $option->pivot->name;
                    }

                    if ($option->slug === 'megethos') {
                        $this->size = $option->pivot->name;
                    }
                }
            }

            $this->options = implode('-', $this->filters);
        }

        $this->updateAvailableOptions();

        $this->findVariant();

        $this->product->unsetRelation('variants');
    }

    public function addToCart(Order $order): void
    {
        if (empty($this->variantId)) {
            $this->showWarningDialog("Παρακαλώ επιλέξτε παραλλαγή");
            $this->skipRender();
            return;
        }

        $quantity = (int)$this->quantity;

        if ($quantity === 0) {
            $this->showWarningDialog("Παρακαλώ εισάγετε ποσότητα");
            $this->skipRender();
            return;
        }

        $product = Product::find($this->variantId);
        if (!$product->canBeBought($quantity, false)) {
            $this->showWarningDialog($product->trademark, __("eshop::order.max_available_stock", ['quantity' => $quantity, 'available' => $product->available_stock]));
            $this->skipRender();
            return;
        }

        DB::transaction(fn() => $this->addProduct($order, $product, $quantity));

        $toast = view('eshop::customer.product.partials.product-toast', compact('product'))->render();
        $this->showSuccessToast($product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
    }

    public function select(VariantType $type, string $slug): void
    {
        $this->filters[$type->id] = $slug;

        $sample = $this->variants->pluck('options')
            ->collapse()
            ->whereIn('pivot.slug', $slug)
            ->first();
        
        if ($type->slug === 'xrwma') {
            $this->color = '';

            if ($sample) {
                $this->color = $sample->pivot->name;
                $sampleProduct = Product::find($sample->pivot->product_id);
                $this->dispatchBrowserEvent('variant-selected', [
                    'image' => $sampleProduct->image?->url()
                ]);
            }
        } elseif ($type->slug === 'megethos') {
            $this->size = '';
            if ($sample) {
                $this->size = $sample->pivot->name;
            }
        }

        $this->options = implode('-', $this->filters);

        $this->updateAvailableOptions();
        $this->findVariant();
    }

    public function getVariantTypesProperty(): Collection
    {
        return $this->variants->pluck('options')->collapse()->unique('id')->sortBy('position');
    }

    public function getUniqueVariantOptionsProperty(): Collection
    {
        return $this->variants
            ->pluck('options')
            ->collapse()
            ->groupBy('pivot.variant_type_id')
            ->map(fn($g) => $g->unique('pivot.name')->sortBy('pivot.name', SORT_NATURAL));
    }

    public function getVariantsProperty(): Collection
    {
        $variants = $this->product
            ->variants()
            ->select('products.*', 'images.disk', 'images.conversions->sm->src as src')
            ->leftJoin('images', function (JoinClause $j) {
                $j->on('images.imageable_id', '=', 'products.id');
                $j->where('images.imageable_type', '=', 'product');
            })
            ->visible()
            ->with('options.translations')
            ->get()
            ->map(function ($p) {
                $p->image = Storage::disk($p->disk)->url($p->src);
                return $p;
            });

        (new \Illuminate\Database\Eloquent\Collection($variants->pluck('options')->collapse()->pluck('pivot')))->load('translations');
        return $variants;
    }

    public function isAvailable(int $variant_type_id, string $option_slug): bool
    {
        $filters = collect($this->filters)->put($variant_type_id, $option_slug);

        return $this->variants
            ->filter(fn($v) => $v->canBeBought(1, false) && $filters->diff($v->options->pluck('pivot.slug'))->isEmpty())
            ->isNotEmpty();
    }

    private function updateAvailableOptions(): void
    {
        $filters = collect($this->filters);

        $this->available = $this->variants
            ->filter(fn($v) => $v->canBeBought(1, false) && $filters->diff($v->options->pluck('pivot.slug'))->isEmpty())
            ->pluck('options')
            ->collapse()
            ->groupBy('id')
            ->map(fn($g) => $g->pluck('pivot.slug')->unique())
            ->all();
    }

    private function findVariant(): void
    {
        $variantId = $this->variants
            ->pluck('options')
            ->collapse()
            ->whereIn('pivot.slug', $this->filters)
            ->groupBy('pivot.product_id')
            ->filter(fn($c) => $c->count() === $this->product->variantTypes->count())
            ->keys()
            ->first();

        $this->variantId = $variantId ?? 0;

        if ($this->variantId !== 0 && $variant = Product::find($this->variantId)) {
            $image = $variant->image ? $variant->image->url($variant->has_watermark ? 'wm' : null) : "";
            $images = $variant->images('gallery')->get()->map(fn($i) => $i->url('sm'))->all();
            $this->dispatchBrowserEvent('variant-selected', compact('image', 'images'));
        }
    }

    public function render(): Renderable
    {
        return view('eshop::customer.product.wire.product-variants-buttons-with-images', [
            'variant'       => $this->variantId !== 0 ? Product::find($this->variantId) : null,
            'variants'      => $this->variants,
            'uniqueOptions' => $this->uniqueVariantOptions
        ]);
    }
}
