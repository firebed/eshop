<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\RendersProduct;
use Eshop\Livewire\Dashboard\Product\Traits\SavesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\WithProductGroupAttributes;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Firebed\Components\Livewire\Traits\SendsNotifications;
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
    use SavesProduct {
        makeProduct as baseMakeProduct;
    }
    use WithProductGroupAttributes;
    use RendersProduct;

    public array $variant_types = [''];

    protected function makeProduct(): Product
    {
        $product = $this->baseMakeProduct();
        $product->variants_display = 'Grid';
        $product->preview_variants = true;
        return $product;
    }

    public function save(): void
    {
        $this->validate();

        $this->saveProduct(TRUE);
        $this->saveVariantTypes();

        $this->redirectRoute('products.edit', $this->product);
    }

    public function addVariantType(): void
    {
        $this->variant_types[] = '';
    }

    public function removeVariantType(int $index): void
    {
        unset($this->variant_types[$index]);

        $this->variant_types = array_values($this->variant_types);
    }

    private function saveVariantTypes(): void
    {
        $variantTypes = collect($this->variant_types)->transform(fn($v) => new VariantType([
            'name' => $v,
            'slug' => slugify($v)
        ]));
        $this->product->variantTypes()->saveMany($variantTypes);
    }

    public function render(): Renderable
    {
        return $this->renderCreateProductGroup();
    }
}
