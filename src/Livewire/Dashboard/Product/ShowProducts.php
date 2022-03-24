<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\Datatable\WithSorting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithSelections;
    use WithSorting {
        WithSorting::queryString as sortingQueryString;
    }

    public             $category     = '';
    public             $manufacturer = '';
    public int         $perPage      = 10;
    public string|bool $visible      = '';
    public             $name;
    public string      $sku          = '';

    public $productsCount;
    public $trashCount;

    public function queryString(): array
    {
        return [
            'name'          => ['except' => ''],
            'sku'           => ['except' => ''],
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
        $this->trashCount = Product::exceptVariants()->onlyTrashed()->count();
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

//    public function clearSelections(): void
//    {
//        $this->reset('category', 'manufacturer', 'name');
//    }

    public function makeVisible(bool $visible, AuditModel $audit): void
    {
        DB::transaction(function () use ($visible, $audit) {
            Product::whereKey($this->selected)->update([
                'visible' => $visible
            ]);

            $models = Product::with('category', 'manufacturer', 'unit', 'translations', 'seos')->whereKey($this->selected)->get();
            foreach ($models as $model) {
                $audit->handle($model);
            }

            $this->clearSelections();
        });
    }

    public function getProductsProperty(): LengthAwarePaginator
    {
        $query = Product::query();

        return $query
            ->when(filled($this->sku), fn($q) => $q->where('sku', 'LIKE', $this->sku . '%')->with('parent.translation', 'options'))
            ->when(blank($this->sku), fn($q) => $q->exceptVariants())
            ->when(filled($this->name), function ($q) {
                $keys = Product::search($this->name)->keys();
                return $q->when(filled($keys), fn($q) => $q->whereKey($keys));
//                return $q->with('parent.translation', 'options');
            })
            ->when(!empty($this->category), fn($q) => $q->where('category_id', $this->category))
            ->when(!empty($this->manufacturer), fn($q) => $q->where('manufacturer_id', $this->manufacturer))
            ->with('manufacturer', 'image')
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->withSum('variants', 'stock')
            ->withCount('variants')
            ->with('category.translations', 'translations')
            ->when($this->visible !== '', fn($q) => $q->where('visible', $this->visible))
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
            ->paginate($this->perPage);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.show-products', [
            'products' => $this->products
        ]);
    }

    protected function getModels(): Collection
    {
        return $this->products->getCollection();
    }
}
