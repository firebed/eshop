<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
    public int      $quantity = 1;
    public string   $options  = '';
    public array    $filters  = [];
    public int      $variantId;

    public array $available = [];

    public function mount(): void
    {
        if (filled($this->options)) {
            $uniqueOptions = $this->uniqueVariantOptions->collapse();

            $userSelected = explode('-', $this->options);
            foreach ($userSelected as $f) {
                $option = $uniqueOptions->firstWhere('pivot.slug', $f);
                if ($option !== null) {
                    $this->filters[$option->pivot->variant_type_id] = $f;
                }
            }

            $this->options = implode('-', $this->filters);
        }

        $this->updateAvailableOptions();

        $this->findVariant();
    }

    public function addToCart(Order $order): void
    {
        if (empty($this->variantId)) {
            $this->showWarningDialog("Παρακαλώ επιλέξτε παραλλαγή");
            $this->skipRender();
            return;
        }

        $product = Product::find($this->variantId);
        if (!$product->canBeBought($this->quantity)) {
            $this->showWarningDialog($product->trademark, __("eshop::order.max_available_stock", ['quantity' => $this->quantity, 'available' => $product->available_stock]));
            $this->skipRender();
            return;
        }

        DB::transaction(fn() => $this->addProduct($order, $product, $this->quantity));

        $toast = view('eshop::customer.product.partials.product-toast', compact('product'))->render();
        $this->showSuccessToast($product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
    }

    public function select(VariantType $type, string $slug): void
    {
        $this->filters[$type->id] = $slug;

        $this->options = implode('-', $this->filters);

        $this->updateAvailableOptions();
        $this->findVariant();
    }
    
    public function getVariantTypesProperty(): Collection
    {
        return $this->product->variants->pluck('options')->collapse()->unique('id');
    }

    public function getUniqueVariantOptionsProperty(): Collection
    {
        return $this->product->variants
            ->pluck('options')
            ->collapse()
            ->groupBy('pivot.variant_type_id')
            ->map(fn($g) => $g->unique('pivot.name')->sortBy('pivot.name', SORT_NATURAL));
    }

    public function render(): Renderable
    {
        return view('eshop::customer.product.wire.product-variants-buttons', [
            'variant'       => $this->variantId ? Product::find($this->variantId) : null,
            'variants'      => $this->product->variants,
            'uniqueOptions' => $this->uniqueVariantOptions
        ]);
    }

    protected function queryString(): array
    {
        return [
            'options' => ['except' => '']
        ];
    }

    private function updateAvailableOptions(): void
    {
        $filters = collect($this->filters);

        $this->available = $this->product->variants
            ->filter(fn($v) => $v->canBeBought() && $filters->diff($v->options->pluck('pivot.slug'))->isEmpty())
            ->pluck('options')
            ->collapse()
            ->groupBy('id')
            ->map(fn($g) => $g->pluck('pivot.slug')->unique())
            ->all();
    }

    public function isAvailable(int $variant_type_id, string $option_slug): bool
    {
        $filters = collect($this->filters)->put($variant_type_id, $option_slug);

        return $this->product->variants
            ->filter(fn($v) => $v->canBeBought() && $filters->diff($v->options->pluck('pivot.slug'))->isEmpty())
            ->isNotEmpty();
    }

    private function findVariant(): void
    {
        $variantId = $this->product->variants
            ->pluck('options')
            ->collapse()
            ->whereIn('pivot.slug', $this->filters)
            ->groupBy('pivot.product_id')
            ->filter(fn($c) => $c->count() === $this->product->variantTypes->count())
            ->keys()
            ->first();

        $this->variantId = $variantId ?? 0;

        if ($variant = Product::find($this->variantId)) {
            $image = $variant->image ? $variant->image->url($variant->has_watermark ? 'wm' : null) : "";
            $images = $variant->images('gallery')->get()->map(fn($i) => $i->url('sm'))->all();
            $this->dispatchBrowserEvent('variant-selected', compact('image', 'images'));
        }
    }
}
