<?php

namespace Ecommerce\Livewire\Dashboard\Product;

use Ecommerce\Livewire\Dashboard\Product\Traits\DeletesProduct;
use Ecommerce\Livewire\Dashboard\Product\Traits\RendersProduct;
use Ecommerce\Livewire\Dashboard\Product\Traits\SavesProduct;
use Ecommerce\Livewire\Dashboard\Product\Traits\WithProductAttributes;
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
class EditProduct extends Component
{
    use SendsNotifications;

    use SavesProduct;
    use DeletesProduct;
    use WithProductAttributes;
    use RendersProduct;

    public function save(): void
    {
        $this->validate();
        $this->saveProduct(false);

        $this->showSuccessToast('Product saved!');
    }

    public function render(): Renderable
    {
        return $this->renderEditProduct();
    }
}
