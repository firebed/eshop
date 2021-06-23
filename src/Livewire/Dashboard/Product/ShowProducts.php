<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\Datatable\WithSorting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;
    use WithSelections;
    use WithSorting {
        WithSorting::queryString as sortingQueryString;
    }

    public $category     = '';
    public $manufacturer = '';
    public $name;

    public $productsCount;
    public $trashCount;

    public function queryString(): array
    {
        return [
            'name'          => ['except' => ''],
            'category'      => ['except' => ''],
            'manufacturer'  => ['except' => ''],
            'sortField'     => ['except' => 'created_at'],
            'sortDirection' => ['except' => 'desc']
        ];
    }

    public function mount(): void
    {
        $this->setSorting('created_at', 'desc');

        $this->productsCount = Product::exceptVariants()->count();
        $this->trashCount = Product::onlyTrashed()->count();
    }

    public function updating($name): void
    {
        if (in_array($name, ['name', 'category', 'manufacturer'])) {
            $this->resetPage();
        }
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::files()
            ->with('translations', 'parent.translation')
            ->get()
            ->groupBy('parent_id');
    }

    public function getManufacturersProperty(): Collection
    {
        return Manufacturer::all();
    }

    public function clearSelections(): void
    {
        $this->reset('category', 'manufacturer', 'name');
    }

    public function getProductsProperty(): LengthAwarePaginator
    {
        return Product
            ::exceptVariants()
            ->when(!empty($this->category), fn($q) => $q->where('category_id', $this->category))
            ->when($this->name, function ($q, $name) {
                $q->where(function ($b) use ($name) {
                    $b->where('slug', 'LIKE', "%$this->name%");
                    $b->orWhereHas('translations', fn($c) => $c->matchAgainst($name));
                });
            })
            ->with('manufacturer', 'image')
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->withSum('variants', 'stock')
            ->withcount('variants')
            ->with('category.translations', 'translations')
            ->when($this->sortField, function ($q, $sf) {
                switch ($sf) {
                    case 'name':
                        $q->select(['products.*']);
                        $q->joinTranslation()->orderBy('translation', $this->sortDirection);
                        break;
                    case 'price':
                        $q->orderBy('variants_min_price', $this->sortDirection);
                        break;
                    case 'stock':
                    case 'sku':
                    case 'variants_count':
                    case 'created_at':
                        $q->orderBy($this->sortField, $this->sortDirection);
                        break;
                }
            })
            ->paginate(10);
    }

    protected function getModels(): Collection
    {
        return $this->products->getCollection();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.show-products', [
            'products' => $this->products
        ]);
    }
}
