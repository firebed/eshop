<?php

namespace Ecommerce\View\Components;

use Ecommerce\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ProductProperties extends Component
{
    public Collection $properties;
    public Collection $choices;

    /**
     * Create a new component instance.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        if ($product->isVariant()) {
            $product = $product->parent;
        }
        $this->properties = $product->properties()->with('translation')->get()->unique();
        $this->choices = $product->choices()->with('translation')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable|string
     */
    public function render(): Renderable|string
    {
        return $this->properties->isEmpty() ? "" : view('com::customer.product.partials.product-properties');
    }
}
