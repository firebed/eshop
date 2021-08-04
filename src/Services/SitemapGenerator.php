<?php

namespace Eshop\Services;

use Eshop\Models\Lang\Locale;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Firebed\Sitemap\Image;
use Firebed\Sitemap\Sitemap;
use Firebed\Sitemap\SitemapIndex;
use Firebed\Sitemap\Url;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL as BaseURL;

class SitemapGenerator
{
    public int $total_sitemaps = 0;
    public int $total_urls     = 0;

    public function generate(): void
    {
        $this->increaseMemoryLimit();

        $alternateLocales = Locale::where('name', '!=', app()->getLocale())->get();

        $pages = $this->generatePagesSitemap($alternateLocales);
        $this->writeSitemap($pages, 'sitemaps/pages.xml');

        $categories = $this->generateCategoriesSitemap($alternateLocales);
        $this->writeSitemap($categories, 'sitemaps/categories.xml');

        $products = $this->generateProductsSitemap($alternateLocales);
        $this->writeSitemap($products, 'sitemaps/products.xml');

        $index = (new SitemapIndex())
            ->addSitemapIf($pages !== NULL, fn() => [BaseURL::asset('sitemaps/pages.xml'), now()])
            ->addSitemapIf($categories !== NULL, fn() => [BaseURL::asset('sitemaps/categories.xml'), now()])
            ->addSitemapIf($products !== NULL, fn() => [BaseURL::asset('sitemaps/products.xml'), now()]);

        if (!$index->isEmpty()) {
            $index->writeToDisk('public', 'sitemap.xml');
        }
    }

    private function increaseMemoryLimit(): void
    {
        ini_set('memory_limit', "1G");
    }

    private function generatePagesSitemap(Collection $alternateLocales): Sitemap|null
    {
        $sitemap = new Sitemap();

        $home = new URL(route('home', app()->getLocale()));
        foreach ($alternateLocales as $locale) {
            $home->addAlternate(route('home', $locale->name), $locale->name);
        }

        $sitemap->addUrl($home);
        return $sitemap;
    }

    private function generateCategoriesSitemap(Collection $alternateLocales): Sitemap|null
    {
        $categories = Category::with('image')->latest()->get();
        if ($categories->isEmpty()) {
            return NULL;
        }

        $sitemap = new Sitemap();
        foreach ($categories as $category) {
            $url = new Url(categoryRoute($category), $category->updated_at, Url::CHANGE_FREQ_MONTHLY);

            foreach ($alternateLocales as $locale) {
                $url->addAlternate(categoryRoute($category, locale: $locale->name), $locale->name);
            }

            if ($category->image) {
                $url->addImage($category->image->url(), $category->name);
            }

            $sitemap->addUrl($url);
        }

        return $sitemap;
    }

    private function generateProductsSitemap(Collection $alternateLocales): Sitemap|null
    {
        $products = Product::with('category', 'images', 'translations')->latest()->get();
        if ($products->isEmpty()) {
            return NULL;
        }

        $sitemap = new Sitemap();
        foreach ($products as $product) {
            $url = new Url();
            $url->lastmod = $product->updated_at;
            $url->changefreq = Url::CHANGE_FREQ_MONTHLY;

            $url->loc = $product->isVariant()
                ? variantRoute($product, $products->firstWhere('parent_id', $product->parent_id), $product->category)
                : productRoute($product, $product->category);

            foreach ($alternateLocales as $locale) {
                $alternate = $product->isVariant()
                    ? variantRoute($product, $products->firstWhere('parent_id', $product->parent_id), $product->category, $locale->name)
                    : productRoute($product, $product->category, $locale->name);

                $url->addAlternate($alternate, $locale->name);
            }

            foreach ($product->images as $image) {
                $url->addImage($image->url(), $product->name);
            }

            $sitemap->addUrl($url);
        }

        return $sitemap;
    }

    public function shouldUpdate(): bool
    {
        if (!$this->getDisk()->exists('sitemap.xml')) {
            return TRUE;
        }

        $lastmod = Carbon::createFromTimestamp($this->getDisk()->lastModified('sitemap.xml'));

        $product = Product::latest('updated_at')->first();
        if ($product?->updated_at->gt($lastmod)) {
            return TRUE;
        }

        $category = Category::latest('updated_at')->first();
        return $category?->updated_at->gt($lastmod);
    }

    private function writeSitemap(Sitemap|null $sitemap, string $path): void
    {
        if ($sitemap === NULL) {
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