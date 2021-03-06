<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Media\Image;
use Eshop\Models\Product\Product;
use Firebed\Components\Livewire\Traits\SendsNotifications;
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
        return view('eshop::dashboard.product-images.wire.show-product-images', [
            'images' => $images
        ]);
    }
}
