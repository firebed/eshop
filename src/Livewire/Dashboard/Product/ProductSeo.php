<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Category;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class ProductSeo
 * @package Eshop\Livewire\Dashboard\Product
 *
 * @method ?Category category
 */
class ProductSeo extends Component
{
    public ?int    $categoryId  = NULL;
    public ?string $productName = "";
    public ?string $url         = "";
    public ?string $slug        = "";
    public ?string $title       = "";
    public ?string $description = "";

    public function mount(): void
    {
        $this->updateUrl();
    }

    public function updatedCategoryId(): void
    {
        $this->updateTitle();
        $this->updateUrl();
    }

    public function updatedTitle(): void
    {
        $this->updateUrl();
    }

    public function updatedSlug(): void
    {
        $this->updateUrl();
    }

    public function updatedDescription(): void
    {
        $this->description = str($this->description)->replace("\n", ' ')->limit(160);
    }

    public function setProductName($productName): void
    {
        $this->productName = $productName;
        $this->updateTitle();
        $this->updateSlug();
        $this->updateUrl();
    }

    private function updateUrl(): void
    {
        $this->url = "/";
        if ($this->category) {
            $this->url = route('customer.categories.show', [app()->getLocale(), $this->category->slug], FALSE);
        }

        $this->url = str_replace('/', ' &rsaquo; ', $this->url . '/' . $this->slug);
    }

    private function updateTitle(): void
    {
        $this->title = $this->category
            ? $this->category->name . ' ' . $this->productName
            : $this->productName;
    }

    private function updateSlug(): void
    {
        $this->slug = slugify($this->productName);
    }

    public function getCategoryProperty(): ?Category
    {
        return $this->categoryId !== NULL
            ? Category::find($this->categoryId)
            : NULL;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.product-seo');
    }
}