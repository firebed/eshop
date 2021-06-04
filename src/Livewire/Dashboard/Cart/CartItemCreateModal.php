<?php

namespace Ecommerce\Livewire\Dashboard\Cart;

use Ecommerce\Models\Cart\CartProduct;
use Ecommerce\Models\Product\Category;
use Ecommerce\Models\Product\Product;
use Ecommerce\Repository\Contracts\CartContract;
use Firebed\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CartItemCreateModal extends Component
{
    use SendsNotifications;
    use WithCRUD {
        create as baseCreate;
    }

    public int $cartId;
    public     $categoryId = "";
    public     $productId  = "";
    public     $variantId  = "";

    public $categories = [];
    public $products   = [];
    public $variants   = [];

    protected function rules(): array
    {
        return [
            'cartId'     => ['required', 'numeric'],
            'categoryId' => ['required', 'numeric'],
            'productId'  => ['required', 'numeric'],
            'variantId'  => ['nullable', 'numeric', Rule::requiredIf(fn() => Product::find($this->productId)->has_variants)],

            'model.quantity'      => ['required', 'numeric', 'min:1'],
            'model.price'         => ['required', 'numeric', 'min:0'],
            'model.compare_price' => ['required', 'numeric', 'min:0'],
            'model.discount'      => ['required', 'numeric', 'between:0,1'],
            'model.vat'           => ['required', 'numeric', 'between:0,1'],
        ];
    }

    public function updatedCategoryId($id): void
    {
        $this->products = Product::query()
            ->select('products.id', 'has_variants', 'price', 'discount')
            ->where('category_id', $id)
            ->exceptVariants()
            ->joinTranslation()
            ->orderBy('name')
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->name])
            ->all();

        $this->updateModel($this->model->quantity, 0, 0, 0, 0);
        $this->skipRender();
    }

    public function updatedProductId($id): void
    {
        $product = Product::find($id);
        $this->variants = $product
            ->variants()
            ->select('id', 'sku', 'price', 'discount')
            ->with('options')
            ->get()
            ->sortBy('options.pivot.value', SORT_NATURAL | SORT_FLAG_CASE)
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->sku . ' ' . $p->options->pluck('pivot.value')->join(' - ')])
            ->all();

        if (!$product->has_variants) {
            $this->updateModel($this->model->quantity, $product->price, $product->compare_price, $product->discount, $product->vat);
        } else {
            $this->updateModel($this->model->quantity, 0, 0, 0, 0);
        }

        $this->skipRender();
    }

    public function updatedVariantId($id): void
    {
        $variant = Product::find($id);
        $this->updateModel($this->model->quantity, $variant->price, $variant->compare_price, $variant->discount, $variant->vat);

        $this->skipRender();
    }

    private function updateModel($quantity, $price, $comparePrice, $discount, $vat): void
    {
        $this->model->quantity = $quantity;
        $this->model->price = $price;
        $this->model->compare_price = $comparePrice;
        $this->model->discount = $discount;
        $this->model->vat = $vat;
    }

    public function create(): void
    {
        $this->reset('categoryId', 'productId', 'variantId', 'categories', 'products', 'variants');

        $this->categories = Category::query()
            ->select('categories.id')
            ->files()
            ->joinTranslation()
            ->orderBy('name')
            ->get()
            ->map(fn($c) => ['id' => $c->id, 'name' => $c->name])
            ->all();

        $this->baseCreate();
        $this->updateModel(1, 0, 0, 0, 0);
    }

    protected function makeEmptyModel(): CartProduct
    {
        return new CartProduct();
    }

    protected function findModel($id): CartProduct
    {
        return CartProduct::find($id);
    }

    public function save(): void
    {
        $this->validate();

        $this->model->product_id = filled($this->variantId)
            ? $this->variantId
            : $this->productId;

        $contract = app(CartContract::class);
        DB::transaction(fn() => $contract->attachCartProduct($this->cartId, $this->model));

        $this->showSuccessToast('Product added to cart!');
        $this->emit('cart-items-created');
        $this->showEditingModal = false;
    }

    public function render(): View
    {
        return view('dashboard.cart.livewire.cart-item-create-modal');
    }
}
