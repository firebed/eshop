<?php


namespace Eshop\Livewire\Dashboard\Cart;


use Eshop\Livewire\Dashboard\Cart\Traits\AppliesBulkDiscount;
use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartProduct;
use Eshop\Repository\Contracts\CartContract;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCart extends Component
{
    use SendsNotifications;
    use TrimStrings;
    use AppliesBulkDiscount;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    public Cart $cart;

    protected $listeners = [
        'cart-items-created'  => '$refresh',
        'cart-status-updated' => '$refresh'
    ];

    protected array $rules = [
        'model.cart_id'    => ['required', 'numeric'],
        'model.product_id' => ['required', 'numeric'],
        'model.quantity'   => ['required', 'numeric', 'min:1'],
        'model.price'      => ['required', 'numeric', 'min:0'],
        'model.discount'   => ['required', 'numeric', 'between:0,1'],
    ];

    protected function makeEmptyModel(): CartProduct
    {
        return new CartProduct([
            'product_id' => "",
            'quantity'   => 1,
            'price'      => 0,
            'discount'   => 0
        ]);
    }

    protected function findModel($id): CartProduct
    {
        return CartProduct::find($id);
    }

    public function getProductsProperty(): Collection
    {
        return $this->cart->products()
            ->oldest('cart_product.created_at')
            ->with('options', 'translation', 'image', 'parent.translation')
            ->get();
    }

    protected function getModels(): Collection
    {
        return $this->products->pluck('pivot');
    }

    protected function deleteRows(): ?int
    {
        $contract = app(CartContract::class);
        return DB::transaction(function () use ($contract) {
            $result = $contract->deleteCartItems($this->cart, $this->selected);
            $this->emit('cart-items-updated');
            return $result;
        });
    }

    public function save(): void
    {
        $this->validate();

        $contract = app(CartContract::class);
        DB::transaction(fn() => $contract->updateCartItem($this->model));

        $this->showSuccessToast('Cart items saved!');
        $this->emit('cart-items-updated');
        $this->showEditingModal = false;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.show-cart', [
            'products' => $this->products,
        ]);
    }
}
