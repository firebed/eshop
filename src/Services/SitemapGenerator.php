<?php

namespace Eshop\Services;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Firebed\Sitemap\Sitemap;
use Firebed\Sitemap\SitemapIndex;
use Firebed\Sitemap\Url;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL as BaseURL;

class SitemapGenerator
{
    public int $total_sitemaps = 0;
    public int $total_urls     = 0;

    public function generate(): void
    {
        $this->increaseMemoryLimit();

        $pages = $this->generatePagesSitemap();
        $this->writeSitemap($pages, 'sitemaps/pages.xml');

        $categories = $this->generateCategoriesSitemap();
        $this->writeSitemap($categories, 'sitemaps/categories.xml');

        $products = $this->generateProductsSitemap();
        $this->writeSitemap($products, 'sitemaps/products.xml');

        $index = (new SitemapIndex())
            ->addSitemapIf($pages !== null, fn() => [BaseURL::asset('sitemaps/pages.xml'), now()])
            ->addSitemapIf($categories !== null, fn() => [BaseURL::asset('sitemaps/categories.xml'), now()])
            ->addSitemapIf($products !== null, fn() => [BaseURL::asset('sitemaps/products.xml'), now()]);

        if (!$index->isEmpty()) {
            $index->writeToDisk('public', 'sitemap.xml');
        }
    }

    public function shouldUpdate(): bool
    {
        if (!$this->getDisk()->exists('sitemap.xml')) {
            return true;
        }

        $lastmod = Carbon::createFromTimestamp($this->getDisk()->lastModified('sitemap.xml'));

        $product = Product::latest('updated_at')->first();
        if ($product?->updated_at->gt($lastmod)) {
            return true;
        }

        $category = Category::latest('updated_at')->first();
        return $category?->updated_at->gt($lastmod);
    }

    private function increaseMemoryLimit(): void
    {
        ini_set('memory_limit', "1G");
    }

    private function generatePagesSitemap(): Sitemap|null
    {
        $sitemap = new Sitemap();

        $sitemap->addUrl('/', today()->startOfMonth(), Url::CHANGE_FREQ_MONTHLY, 1);
        $sitemap->addUrl(route('home', app()->getLocale()), today()->startOfMonth(), Url::CHANGE_FREQ_MONTHLY, 1);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'terms-of-service']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'data-protection']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'return-policy']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'secure-transactions']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'shipping-methods']), today()->startOfMonth(), Url::CHANGE_FREQ_MONTHLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'payment-methods']), today()->startOfMonth(), Url::CHANGE_FREQ_MONTHLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'login']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'register']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'cart']), today()->startOfYear(), Url::CHANGE_FREQ_YEARLY);
        $sitemap->addUrl(route('pages.show', [app()->getLocale(), 'offers']), today(), Url::CHANGE_FREQ_DAILY);

        return $sitemap;
    }

    private function generateCategoriesSitemap(): Sitemap|null
    {
        $categories = Category::with('translation', 'image')->visible()->latest()->get();
        if ($categories->isEmpty()) {
            return null;
        }

        $sitemap = new Sitemap();
        foreach ($categories as $category) {
            $url = new Url(categoryRoute($category), $category->updated_at, Url::CHANGE_FREQ_MONTHLY);

            if ($category->image) {
                $url->addImage($category->image->url(), $category->name);
            }

            $sitemap->addUrl($url);
        }

        return $sitemap;
    }

    private function generateProductsSitemap(): Sitemap|null
    {
        $products = Product::with('category', 'images', 'translations')->visible()->latest()->get();
        if ($products->isEmpty()) {
            return null;
        }

        $sitemap = new Sitemap();
        foreach ($products as $product) {
            $url = new Url();
            $url->lastmod = $product->updated_at;
            $url->changefreq = Url::CHANGE_FREQ_MONTHLY;

            $url->loc = $product->isVariant()
                ? variantRoute($product, $products->firstWhere('parent_id', $product->parent_id), $product->category)
                : productRoute($product, $product->category);

            foreach ($product->images as $image) {
                $url->addImage($image->url(), $product->name);
            }

            $sitemap->addUrl($url);
        }

        return $sitemap;
    }

    private function writeSitemap(Sitemap|null $sitemap, string $path): void
    {
        if ($sitemap === null) {
            return;
        }

        $sitemap?->writeToDisk('public', $path);
        $this->total_urls += $sitemap->totalUrls();
        ++$this->total_sitemaps;
    }

    private function getDisk(): Filesystem
    {
        return Storage::disk('public');
    }
}