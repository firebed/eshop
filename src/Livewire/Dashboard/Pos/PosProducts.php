<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Carbon\Carbon;
use Eshop\Models\Product\Product;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property Collection products
 */
class PosProducts extends Component
{
    use SendsNotifications;
    
    public ?Carbon $submitted_at = null;
    public int     $cart_id;
    public array   $items        = [];
    public float   $fees         = 0;
    public float   $total        = 0;
    public float   $weight       = 0;

    public string $barcode = "";

    protected $listeners = ['addProduct', 'updateProduct', 'setProcessingFees'];

    public function mount(): void
    {
        $this->updateTotal();
    }

    public function setProcessingFees($fees): void
    {
        $this->fees = $fees;
        $this->updateTotal(false);
    }

    public function addProduct(Product $product): void
    {
        if (isset($this->items[$product->id])) {
            $this->items[$product->id]['quantity']++;
        } else {
            $this->items[$product->id] = [
                'quantity' => 1,
                'price'    => $product->price,
                'discount' => $product->discount
            ];
        }

        $this->updateTotal();
        
        $this->showSuccessToast('Προστέθηκε!', delay: 500);
    }

    public function updateProduct(int $productId, int $quantity, float $price, float $discount): void
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId] = [
                'quantity' => $quantity,
                'price'    => $price,
                'discount' => $discount
            ];
        }

        $this->updateTotal();
    }

    public function removeProduct(int $id): void
    {
        unset($this->items[$id]);
        $this->updateTotal();
    }

    public function searchBarcode(): void
    {
        $barcode = trim($this->barcode);
        if (blank($barcode)) {
            return;
        }

        $product = Product::firstWhere('barcode', $barcode);

        if ($product) {
            $this->addProduct($product);
            $this->barcode = "";
        } else {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'body' => "Δεν βρέθηκε προϊόν με barcode '$barcode'"]);
        }
    }

    public function getProductsProperty(): Collection
    {
        return Product::select('id', 'parent_id', 'weight')
            ->with(['translation' => fn($q) => $q->select('translatable_id', 'translation'),
                    'parent'      => fn($q) => $q->select('id')->with(['translation' => fn($q) => $q->select('translatable_id', 'translation')]),
                    'image',
                    'variantOptions.translation'
            ])
            ->findMany(array_keys($this->items));
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.pos.wire.pos-products-table', [
            'products' => $this->products,
        ]);
    }

    private function updateTotal($emit = true): void
    {
        $items = $this->items;
        $this->total = array_reduce($items, static function ($carry, $item) {
            return $carry + $item['quantity'] * $item['price'] * (1 - $item['discount']);
        }, (float)$this->fees);

        $this->weight = array_reduce(array_keys($items), function ($carry, $key) use ($items) {
            $qty = $items[$key]['quantity'];
            $weight = $this->products->find($key)?->weight ?? 0;
            return $carry + $qty * $weight;
        }, 0);
        
        if ($emit) {
            $this->emit('updateTotals', $this->weight, $this->total);
        }
    }
}