<?php

namespace Eshop\Services;

use Eshop\Models\Lang\Locale;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
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

        $locales = Locale::get();

//        $pages = $this->generatePagesSitemap($locales);
//        $this->writeSitemap($pages, 'sitemaps/pages.xml');

        $categories = $this->generateCategoriesSitemap($locales);
        $this->writeSitemap($categories, 'sitemaps/categories.xml');

//        $products = $this->generateProductsSitemap($locales);
//        $this->writeSitemap($products, 'sitemaps/products.xml');

        $index = (new SitemapIndex())
//            ->addSitemapIf($pages !== NULL, fn() => [BaseURL::asset('sitemaps/pages.xml'), now()])
            ->addSitemapIf($categories !== null, fn() => [BaseURL::asset('sitemaps/categories.xml'), now()]);
//            ->addSitemapIf($products !== NULL, fn() => [BaseURL::asset('sitemaps/products.xml'), now()]);

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

    private function generatePagesSitemap(Collection $locales): Sitemap|null
    {
        $sitemap = new Sitemap();

        foreach ($locales as $locale) {
            $home = new URL(route('home', $locale->name));
            foreach ($locales as $alternateLocale) {
                $home->addAlternate(route('home', $alternateLocale->name), $alternateLocale->name);
            }
            $sitemap->addUrl($home);
        }
        return $sitemap;
    }

    private function generateCategoriesSitemap(Collection $locales): Sitemap|null
    {
        $categories = Category::with('translation', 'image')->visible()->latest()->get();
        if ($categories->isEmpty()) {
            return null;
        }

        $sitemap = new Sitemap();
        foreach ($locales as $locale) {
            foreach ($categories as $category) {
                $url = new Url(categoryRoute($category, locale: $locale->name), $category->updated_at, Url::CHANGE_FREQ_MONTHLY);

                foreach ($locales as $alternateLocale) {
                    $url->addAlternate(categoryRoute($category, locale: $alternateLocale->name), $alternateLocale->name);
                }

                if ($category->image) {
                    $url->addImage($category->image->url(), $category->name);
                }

                $sitemap->addUrl($url);
            }
        }

        return $sitemap;
    }

    private function generateProductsSitemap(Collection $locales): Sitemap|null
    {
        $products = Product::with('category', 'images', 'translations')->latest()->get();
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

            foreach ($locales as $locale) {
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