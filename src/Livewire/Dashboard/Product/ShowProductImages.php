<?php


namespace Ecommerce\Livewire\Dashboard\Product;


use Ecommerce\Models\Media\Image;
use Ecommerce\Models\Product\Product;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowProductImages extends Component
{
    use WithFileUploads,
        SendsNotifications;

    public ?Product $product;
    public          $uploads = [];

    public function mount(int $productId): void
    {
        $this->product = Product::findOrFail($productId);
    }

    public function save(): void
    {
        $this->validate([
            'uploads.*' => 'image|max:2048'
        ]);

        foreach ($this->uploads as $image) {
            $this->product->saveImage($image, 'gallery');
        }

        $this->showSuccessToast(__("Images saved successfully"));

        $this->reset('uploads');
    }

    public function delete(Image $image): void
    {
        $image->delete();

        $this->showSuccessToast(__("The image was deleted successfully"));
    }

    public function render(): Renderable
    {
        $images = $this->product->images('gallery')->paginate();
        return view('dashboard.product-images.wire.show-product-images', [
            'images' => $images
        ]);
    }
}
