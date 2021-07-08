<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\ProductRepository;
use Illuminate\Support\Facades\DB;

trait SavesProduct
{
    use ManagesAttributes;
    use SavesProductImage;
    use CreatesEmptyProduct;

    public Product   $product;
    public ?Category $category = NULL;

    public string $name        = '';
    public string $description = '';

    public function mountSavesProduct(): void
    {
        if (!isset($this->product)) {
            $this->product = $this->makeProduct();
        } else {
            $this->category = Category::find($this->product->category_id);
        }

        $this->name = $this->product->name ?? '';
        $this->description = $this->product->description ?? '';
        $this->product->manufacturer_id = $this->product->manufacturer_id ?: '';
    }

    public function updatedProductCategoryId($id): void
    {
        $this->category = Category::find($id);
        $this->loadAttributes();
    }

    public function updatedName(): void
    {
        if ($this->product->id === NULL) {
            $this->product->slug = slugify($this->name);
            $this->skipRender();
        }
    }

    protected function saveProduct(): void
    {
        $this->product->name = $this->name;
        $this->product->description = $this->trim($this->description);
        $this->product->manufacturer_id = $this->trim($this->product->manufacturer_id);

        DB::transaction(function () {
            $service = new ProductRepository();
            $service->save($this->product);
            $this->saveImage();
            $this->saveAttributes();
        });
    }

    protected function getModel(): Product
    {
        return $this->product;
    }
}
