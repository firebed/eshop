<?php


namespace Ecommerce\Livewire\Dashboard\Product\Traits;


use Livewire\WithFileUploads;

trait SavesProductImage
{
    use WithFileUploads;

    public $image;

    public function mountManagesImage(): void
    {
        $this->image = $this->getModel()->image;
    }

    public function saveImage(): void
    {
        if (!is_null($this->image)) {
            $model = $this->getModel();

            $image = $model->image;
            if ($image) {
                $image->delete();
            }

            $model->saveImage($this->image);
        }
    }

    public function resetImage(): void
    {
        $this->reset('image');
    }

    abstract protected function getModel();
}
