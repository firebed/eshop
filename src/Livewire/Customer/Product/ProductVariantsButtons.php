<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
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

    public ?Product $product  = NULL;
    public int      $quantity = 1;
    public string   $options  = '';
    public array    $filters  = [];
    public int      $variantId;

    protected function queryString(): array
    {
        return [
            'options' => ['except' => '']
        ];
    }

    public function mount(): void
    {
        if (filled($this->options)) {
            $uniqueOptions = $this->uniqueVariantOptions->collapse();

            $userSelected = explode('-', $this->options);
            foreach ($userSelected as $f) {
                $option = $uniqueOptions->firstWhere('pivot.slug', $f);
                if ($option !== NULL) {
                    $this->filters[$option->pivot->variant_type_id] = $f;
                }
            }

            $this->options = implode('-', $this->filters);
        }

        $this->findVariant();
    }

    public function addToCart(Order $order): void
    {
        $product = Product::find($this->variantId);
        if (!$this->addProduct($order, $product, $this->quantity)) {
            return;
        }

        $toast = view('eshop::customer.product.partials.product-toast', compact('product'))->render();
        $this->showSuccessToast($product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
    }

    public function select(VariantType $type, string $slug): void
    {
        $this->filters[$type->id] = $slug;

        $this->options = implode('-', $this->filters);

        $this->findVariant();
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

        if ($variant = Product::find($this->variantId)) {
            $image = $variant->image->url();
            $images = $variant->images('gallery')->get()->map(fn($i) => $i->url('sm'))->all();
            $this->dispatchBrowserEvent('variant-selected', compact('image', 'images'));
        }
    }

    public function getVariantsProperty(): Collection
    {
        return $this->product
            ->variants()
            ->with('options')
            ->visible()
            ->get()
            ->sortBy(['sku', 'variant_values'], SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function getUniqueVariantOptionsProperty(): Collection
    {
        return $this->variants
            ->pluck('options')
            ->collapse()
            ->groupBy('pivot.variant_type_id')
            ->map(fn($g) => $g->unique('pivot.value')->sort());
    }

    public function render(): Renderable
    {
        return view('eshop::customer.product.wire.product-variants-buttons', [
            'variant'       => $this->variantId ? Product::find($this->variantId) : NULL,
            'variants'      => $this->variants,
            'uniqueOptions' => $this->uniqueVariantOptions
        ]);
    }
}
