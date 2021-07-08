<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\Vat;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

trait RendersProduct
{
    protected function renderCreateProduct(): Renderable
    {
        return $this->renderProduct('create');
    }

    protected function renderEditProduct(): Renderable
    {
        return $this->renderProduct('edit');
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::files()
            ->with('translations', 'parent.translation')
            ->get()
            ->groupBy('parent_id');
    }

    protected function renderProduct($view): Renderable
    {
        if (filled($this->category)) {
            $this->category->properties->loadMissing('translation', 'choices.translation');
            $properties = $this->category->properties->all();
        }

        return view("eshop::dashboard.product.wire.$view-product", [
            'categories'    => $this->categories,
            'manufacturers' => Manufacturer::all(),
            'units'         => Unit::all(),
            'vats'          => Vat::all(),
            'props'         => $properties ?? []
        ]);
    }
}
