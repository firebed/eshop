<?php

namespace Eshop\Livewire\Dashboard\Channel;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Channel;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChannel extends Component
{
    use WithPagination;

    public Channel $channel;

    public array $selectedCategories    = [];
    public array $selectedManufacturers = [];

    protected $queryString = [
        'selectedCategories'    => ['except' => []],
        'selectedManufacturers' => ['except' => []],
    ];

    public function updated($key)
    {
        if ($key === 'selectedCategories' || $key === 'selectedManufacturers') {
            $this->resetPage();
        }
    }

    public function resetSelectedCategories(): void
    {
        $this->selectedCategories = [];
    }

    public function resetSelectedManufacturers(): void
    {
        $this->selectedManufacturers = [];
    }

    public function render(): Renderable
    {
        $keys = $this->channel->products()->with('parent')->get();

        $missingImages = $this->channel->products()->whereDoesntHave('image')->count();
        $inactive = Product::exceptParents()->whereKeyNot($keys->pluck('id'))->count();

        $categories = Category::whereKey($keys->pluck('category_id')->unique())
            ->withCount(['products' => fn($q) => $q->whereKey($keys->pluck('id'))->when(filled($this->selectedManufacturers), fn($q) => $q->whereIn('manufacturer_id', $this->selectedManufacturers))])
            ->with('translation')
            ->get();

        $manufacturers = Manufacturer::whereKey($keys->pluck('manufacturer_id')->unique())
            ->withCount(['products' => fn($q) => $q->whereKey($keys->pluck('id'))->when(filled($this->selectedCategories), fn($q) => $q->whereIn('category_id', $this->selectedCategories))])
            ->get();

        $products = $this->channel
            ->products()
            ->when(filled($this->selectedCategories), fn($q) => $q->whereIn('category_id', $this->selectedCategories))
            ->when(filled($this->selectedManufacturers), fn($q) => $q->whereIn('manufacturer_id', $this->selectedManufacturers))
            ->orderBy('parent_id')
            ->orderBy('id')
            ->with('variantOptions.translation', 'translation', 'image', 'category', 'parent.translation', 'manufacturer')
            ->paginate();

        $productsCount = $this->channel
            ->products()
            ->when(filled($this->selectedCategories), fn($q) => $q->whereIn('category_id', $this->selectedCategories))
            ->when(filled($this->selectedManufacturers), fn($q) => $q->whereIn('manufacturer_id', $this->selectedManufacturers))
            ->count();

        return view('eshop::dashboard.channel.wire.show-channel', [
            'products'      => $products,
            'categories'    => $categories,
            'manufacturers' => $manufacturers,
            'productsCount' => $productsCount,
            'keys'          => $keys,
            'keysCount'     => $keys->count(),
            'inactive'      => $inactive,
            'missingImages' => $missingImages
        ]);
    }
}