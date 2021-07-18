<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * Class ProductSeo
 * @package Eshop\Livewire\Dashboard\Product
 *
 * @method ?Category category
 */
class VariantSeo extends Component
{
    public ?string $productName = "";
    public ?string $productDescription = "";
    public ?string $productSlug = "";
    public ?string $productUrl  = "";
    public ?string $url         = "";
    public ?string $slug        = "";
    public ?string $title       = "";
    public ?string $description = "";

    public function mount(Product $product): void
    {
        $this->productName = $product->name;
        $this->productDescription = $product->seo->description;
        $this->productSlug = $product->slug;
        $this->productUrl = productRoute($product, absolute: FALSE);
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

    public function setOptions(array $options): void
    {
        $this->slug = $this->productSlug . '-' . slugify($options);
        array_unshift($options, $this->productName);
        $this->title = implode(' ', array_filter($options));
        $this->updateUrl();
    }

    private function updateUrl(): void
    {
        $this->url = str_replace('/', ' &rsaquo; ', $this->productUrl . '/' . $this->slug);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.variant.wire.variant-seo');
    }
}