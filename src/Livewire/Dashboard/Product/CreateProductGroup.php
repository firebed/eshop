<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\RendersProduct;
use Eshop\Livewire\Dashboard\Product\Traits\SavesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\WithProductGroupAttributes;
use Eshop\Models\Product\VariantType;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Class ProductForm
 * @package App\Http\Livewire\Product
 *
 * @property Collection units
 * @property Collection vats
 * @property Collection categories
 *
 */
class CreateProductGroup extends Component
{
    use SendsNotifications;
    use SavesProduct;
    use WithProductGroupAttributes;
    use RendersProduct;

    public array $variant_types = [''];

    public function save(): void
    {
        $this->validate();

        $this->saveProduct(true);
        $this->saveVariantTypes();

        $this->redirectRoute('products.edit', $this->product);
    }

    private function saveVariantTypes(): void
    {
        $variantTypes = collect($this->variant_types)->transform(fn($v) => new VariantType(['name' => $v]));
        $this->product->variantTypes()->saveMany($variantTypes);
    }

    public function render(): Renderable
    {
        return $this->renderCreateProductGroup();
    }
}
