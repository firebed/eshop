<?php

namespace App\Http\Livewire\Dashboard\Product;

use App\Http\Livewire\Dashboard\Product\Traits\DeletesProduct;
use App\Http\Livewire\Dashboard\Product\Traits\RendersProduct;
use App\Http\Livewire\Dashboard\Product\Traits\SavesProduct;
use App\Http\Livewire\Dashboard\Product\Traits\WithProductGroupAttributes;
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
class EditProductGroup extends Component
{
    use SendsNotifications;
    use SavesProduct;
    use DeletesProduct;
    use WithProductGroupAttributes;
    use RendersProduct;

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
