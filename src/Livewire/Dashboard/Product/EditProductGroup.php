<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\DeletesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\RendersProduct;
use Eshop\Livewire\Dashboard\Product\Traits\SavesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\WithProductGroupAttributes;
use Eshop\Models\Product\Product;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Class ProductForm
 * @package Eshop\Livewire\Product
 *
 * @property Collection units
 * @property Collection vats
 * @property Collection categories
 *
 */
class EditProductGroup extends Component
{
    use SendsNotifications;
    use SavesProduct {
        makeProduct as baseMakeProduct;
    }
    use DeletesProduct;
    use WithProductGroupAttributes;
    use RendersProduct;

    protected function makeProduct(): Product
    {
        $product = $this->baseMakeProduct();
        $product->variants_display = 'Grid';
        return $product;
    }

    public function save(): void
    {
        $this->validate();
        $this->saveProduct(true);

        $this->showSuccessToast('Product group saved!');
    }

    public function render(): Renderable
    {
        return $this->renderEditProductGroup();
    }
}
