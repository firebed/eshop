<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\CartProduct;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\CartContract;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CartItemCreateModal extends Component
{
    use SendsNotifications;
    use WithCRUD {
        create as baseCreate;
    }

    public int    $cartId;
    public string $categoryId = "";
    public string $productId  = "";
    public string $variantId  = "";

    public function updatedCategoryId(): void
    {
        $this->updateModel($this->model->quantity, 0, 0, 0, 0);
    }

    public function updatedProductId(): void
    {
        $product = Product::find($this->productId);

        if (!$product->has_variants) {
            $this->updateModel($this->model->quantity, $product->price, $product->compare_price, $product->discount, $product->vat);
        } else {
            $this->updateModel($this->model->quantity, 0, 0, 0, 0);
        }
    }

    public function updatedVariantId($id): void
    {
        $variant = Product::find($id);
        $this->updateModel($this->model->quantity, $variant->price, $variant->compare_price, $variant->discount, $variant->vat);
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::query()
            ->visible()
            ->with('translation', 'parent.translation')
            ->files()
            ->get()
            ->groupBy('parent_id');
    }

    public function getProductsProperty(): Collection
    {
        return Product::query()
            ->select('id', 'has_variants', 'price', 'discount', 'stock')
            ->visible()
            ->where('category_id', $this->categoryId)
            ->exceptVariants()
            ->with('translation')
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'has_variants' => $p->has_variants, 'stock' => $p->stock])
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function getVariantsProperty(): Collection
    {
        return Product::query()
            ->select('id', 'price', 'discount', 'stock')
            ->where('parent_id', $this->productId)
            ->visible()
            ->with('variantOptions.translation')
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->sku . ' ' . $p->optionValues(' - '), 'stock' => $p->stock])
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function create(): void
    {
        $this->reset('categoryId', 'productId', 'variantId');

        $this->baseCreate();
        $this->updateModel(1, 0, 0, 0, 0);
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

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.cart-item-create-modal');
    }

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

    protected function makeEmptyModel(): CartProduct
    {
        return new CartProduct();
    }

    protected function findModel($id): CartProduct
    {
        return CartProduct::find($id);
    }

    private function updateModel($quantity, $price, $comparePrice, $discount, $vat): void
    {
        $this->model->quantity = $quantity;
        $this->model->price = $price;
        $this->model->compare_price = $comparePrice;
        $this->model->discount = $discount;
        $this->model->vat = $vat;
    }
}
