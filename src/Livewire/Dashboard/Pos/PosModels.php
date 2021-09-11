<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\Utils\CategoryBreadcrumbs;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class PosModels extends Component
{
    public ?string $categoryId  = null;
    public ?string $productId   = null;
    protected      $queryString = ['categoryId' => ['except' => ''], 'productId' => ['except' => '']];
    private array  $data;

    public function mount(CategoryBreadcrumbs $breadcrumbs): void
    {
        if (isset($this->categoryId)) {
            $cat = Category::find($this->categoryId);
            if ($cat->isFolder()) {
                $this->loadCategories($this->categoryId, $breadcrumbs);
            } else {
                $this->loadProducts($this->categoryId, $breadcrumbs);
            }
        } elseif (isset($this->productId)) {
            $this->loadVariants($this->productId, $breadcrumbs);
        } else {
            $this->loadCategories(null, $breadcrumbs);
        }
    }

    public function categories(int $parentId = null): Collection|array
    {
        $categories = $parentId !== null
            ? Category::where('parent_id', $parentId)
            : Category::root();

        return $categories->select('id', 'type')->with('translation', 'image')->get();
    }

    public function products(int $categoryId): Collection|array
    {
        return Product::select('id', 'sku', 'has_variants', 'price', 'compare_price', 'discount', 'stock')
            ->where('category_id', '=', $categoryId)
            ->exceptVariants()
            ->withCount('variants')
            ->withMin('variants', 'net_value')
            ->withMax('variants', 'net_value')
            ->with('image', 'translation')
            ->get();
    }

    public function variants(int $productId = null): Collection|array
    {
        return Product::select('id', 'parent_id', 'sku', 'price', 'compare_price', 'discount', 'stock')
            ->where('parent_id', $productId)
            ->with('image', 'translation', 'options', 'parent.translation')
            ->get('id');
    }


    public function loadCategories(null|int $parentId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = $parentId ?? '';
        $this->productId = "";

        $this->data = [
            'categories'  => $this->categories($parentId),
            'breadcrumbs' => $parentId ? $breadcrumbs->handle(Category::find($parentId)) : null
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId' => $this->productId
        ]);
    }

    public function loadProducts(int $categoryId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = $categoryId;
        $this->productId = "";

        $this->data = [
            'products'    => $this->products($categoryId),
            'breadcrumbs' => $breadcrumbs->handle(Category::find($categoryId))
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId' => $this->productId
        ]);
    }

    public function loadVariants(int $productId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = "";
        $this->productId = $productId;

        $product = Product::find($productId);
        $this->data = [
            'product'     => $product,
            'variants'    => $this->variants($productId),
            'breadcrumbs' => $breadcrumbs->handle($product->category)
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId' => $this->productId
        ]);
    }

    public function render(): Renderable
    {
        return view("eshop::dashboard.pos.wire.models", $this->data);
    }
}