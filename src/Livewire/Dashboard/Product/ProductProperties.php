<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Category;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ProductProperties extends Component
{
    public ?int   $categoryId;
    public ?array $properties;

    public function setCategory(int $id): void
    {
        $this->categoryId = $id;
    }

    public function render(): Renderable
    {
        if (isset($this->categoryId) && filled($this->categoryId)) {
            $category = Category::find($this->categoryId);
            $properties = $category->properties->load('translation', 'choices.translation');
        }

        return view('eshop::dashboard.product.wire.product-properties', [
            'props'      => $properties ?? [],
            'properties' => $this->properties ?? []
        ]);
    }
}