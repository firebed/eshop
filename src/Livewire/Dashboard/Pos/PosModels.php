<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\HighlightText;
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
    public string  $search      = '';
    public bool    $editing     = false;
    protected      $queryString = ['categoryId' => ['except' => ''], 'productId' => ['except' => '']];
    private array  $data;

    public function mount(CategoryBreadcrumbs $breadcrumbs): void
    {
        if (isset($this->categoryId)) {
            $category = Category::find($this->categoryId);
            if ($category->isFolder()) {
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

        return $categories->select('id', 'type')->with('translation', 'image')->get()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function products(int $categoryId): Collection|array
    {
        return Product::select('id', 'sku', 'has_variants', 'price', 'compare_price', 'discount', 'stock')
            ->visible()
            ->where('category_id', '=', $categoryId)
            ->exceptVariants()
            ->withCount('variants')
            ->withMin('variants', 'net_value')
            ->withMax('variants', 'net_value')
            ->with('translation', 'image')
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function variants(int $productId = null): Collection|array
    {
        $variants = Product::select('id', 'parent_id', 'sku', 'price', 'compare_price', 'discount', 'visible', 'stock', 'available_gt', 'available')
            ->visible()
            ->where('parent_id', $productId)
            ->with('image', 'translation', 'options', 'parent.translation')
            ->get('id');

        (new Collection($variants->pluck('options')->collapse()->pluck('pivot')))->load('translation');

        return $variants->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function loadCategories(null|int $parentId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = $parentId ?? '';
        $this->productId = "";
        $this->search = "";

        $this->data = [
            'categories'  => $this->categories($parentId),
            'breadcrumbs' => $parentId ? $breadcrumbs->handle(Category::find($parentId)) : null
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId'  => $this->productId
        ]);
    }

    public function loadProducts(int $categoryId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = $categoryId;
        $this->productId = "";
        $this->search = "";

        $this->data = [
            'products'    => $this->products($categoryId),
            'breadcrumbs' => $breadcrumbs->handle(Category::find($categoryId))
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId'  => $this->productId
        ]);
    }

    public function loadVariants(int $productId, CategoryBreadcrumbs $breadcrumbs): void
    {
        $this->categoryId = "";
        $this->productId = $productId;
        $this->search = "";

        $product = Product::find($productId);
        $this->data = [
            'product'     => $product,
            'variants'    => $this->variants($productId),
            'breadcrumbs' => $breadcrumbs->handle($product->category)
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId'  => $this->productId
        ]);
    }

    public function updatedSearch($search): void
    {
        if (blank($search)) {
            $this->loadCategories(null, new CategoryBreadcrumbs());
            return;
        }

        $products = Product::select('id', 'sku', 'has_variants', 'price', 'compare_price', 'discount', 'stock')
            ->visible()
            ->exceptVariants()
            ->whereHas('translations', fn($c) => $c->matchAgainst(trim($search))->where('cluster', 'name'))
            ->withCount('variants')
            ->withMin('variants', 'net_value')
            ->withMax('variants', 'net_value')
            ->with('image', 'translation')
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        $highlighter = new HighlightText();
        foreach ($products as $product) {
            $product->name = $highlighter->handle($search, $product->name, true);
        }
        
        $this->data = [
            'products'    => $products
        ];

        session()->put('pos-models-query', [
            'categoryId' => $this->categoryId,
            'productId'  => $this->productId
        ]);
    }

    public function render(): Renderable
    {
        return view("eshop::dashboard.pos.wire.models", $this->data);
    }
}