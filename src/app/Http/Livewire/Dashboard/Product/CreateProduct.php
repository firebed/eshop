<?php

namespace App\Http\Livewire\Dashboard\Product;

use App\Http\Livewire\Dashboard\Product\Traits\RendersProduct;
use App\Http\Livewire\Dashboard\Product\Traits\SavesProduct;
use App\Http\Livewire\Dashboard\Product\Traits\WithProductAttributes;
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
class CreateProduct extends Component
{
    use SendsNotifications;
    use SavesProduct;
    use WithProductAttributes;
    use RendersProduct;

    public function save(): void
    {
        $this->validate();
        $this->saveProduct(false);

        $this->redirectRoute('products.edit', $this->product);
    }

    public function render(): Renderable
    {
        return $this->renderCreateProduct();
    }
}
