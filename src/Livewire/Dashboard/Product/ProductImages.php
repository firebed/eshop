<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\DeletesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\RendersProduct;
use Eshop\Livewire\Dashboard\Product\Traits\SavesProduct;
use Eshop\Livewire\Dashboard\Product\Traits\WithProductAttributes;
use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Product\Product;
use Eshop\Models\Seo\Seo;
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
class ProductImages extends Component
{
    use SendsNotifications,
        WithFileUploads;

    public $images = [];

    public function updatedImages()
    {
        dd($this->images);
    }

    public function mount(Product $product = null): void
    {

    }

    public function save(): void
    {
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.product-images');
    }
}
