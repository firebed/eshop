<?php


namespace Ecommerce\Livewire\Dashboard\Product\Traits;


use Ecommerce\Models\Product\Category;
use Ecommerce\Models\Product\Manufacturer;
use Ecommerce\Models\Product\Unit;
use Ecommerce\Models\Product\Vat;
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

        return view("com::dashboard.product.livewire.$view-product", [
            'categories'    => $this->categories,
            'manufacturers' => Manufacturer::all(),
            'units'         => Unit::all(),
            'vats'          => Vat::all(),
            'props'         => $properties ?? []
        ]);
    }

    protected function renderCreateProductGroup(): Renderable
    {
        return $this->renderProductGroup('create');
    }

    protected function renderEditProductGroup(): Renderable
    {
        return $this->renderProductGroup('edit');
    }

    public function renderProductGroup($view): Renderable
    {
        if (filled($this->category)) {
            $this->category->properties->loadMissing('translation', 'choices.translation');
            $properties = $this->category->properties->all();
        }

        return view("com::dashboard.product.livewire.$view-product-group", [
            'categories'    => $this->categories,
            'manufacturers' => Manufacturer::all(),
            'props'         => $properties ?? []
        ]);
    }
}
