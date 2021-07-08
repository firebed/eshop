<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\RendersProduct;
use Eshop\Livewire\Dashboard\Product\Traits\SavesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\WithProductAttributes;
use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Product\VariantType;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class ProductForm
 * @package App\Http\Livewire\Product
 *
 * @property Collection units
 * @property Collection vats
 * @property Collection categories
 *
 */
class CreateProduct extends Component
{
    use WithFileUploads,
        SendsNotifications,
        TrimStrings,
        SavesProduct,
        WithProductAttributes,
        RendersProduct;

    public array $variantTypes = [];

    public function save(): void
    {
        $this->validate();
        $has_variants = filled(array_filter($this->variantTypes, static fn($v) => filled($v['name'])));

        $this->product->has_variants = $has_variants;
        $this->saveProduct();
        $this->saveVariantTypes();

        $this->redirectRoute('products.edit', $this->product);
    }

    private function saveVariantTypes(): void
    {
        $variantTypes = collect($this->variantTypes)->transform(fn($v) => new VariantType([
            'name' => $v,
            'slug' => slugify($v)
        ]));

        $this->product->variantTypes()->saveMany($variantTypes);
    }

    public function render(): Renderable
    {
        return $this->renderCreateProduct();
    }
}
