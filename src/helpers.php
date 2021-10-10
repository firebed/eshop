<?php

use Eshop\Actions\Schema\Schema;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Models\User;
use Eshop\Services\SlugGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('format_number')) {
    function format_number($number, $min_fraction_digits = 0, $max_fraction_digits = 2, $locale = null): bool|string
    {
        $formatter = new NumberFormatter($locale ?? app()->getLocale(), NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $min_fraction_digits);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $max_fraction_digits);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);
        return $formatter->format($number);
    }
}

if (!function_exists('get_decimal_point')) {
    function get_decimal_point(): bool|string
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::DECIMAL);
        return $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
    }
}

if (!function_exists('format_currency')) {
    function format_currency($number): string
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);
        return $formatter->formatCurrency($number, config('eshop.currency'));
    }
}

if (!function_exists('format_percent')) {
    function format_percent($number): bool|string
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::PERCENT);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);
        return $formatter->format($number);
    }
}

if (!function_exists('format_weight')) {
    function format_weight($weight): string
    {
        return $weight >= 1000 ? format_number($weight / 1000) . ' kg' : format_number($weight) . ' gr';
    }
}

if (!function_exists('slugify')) {
    function slugify($strings, $separator = '-'): string
    {
        $string = implode($separator, is_array($strings) ? array_filter($strings) : [$strings]);
        return SlugGenerator::getSlug($string, $separator);
    }
}

if (!function_exists('routeHas')) {
    function routeHas(string $name): bool
    {
        return Route::has($name);
    }
}

if (!function_exists('productRouteExists')) {
    function productRouteExists(): bool
    {
        return Route::has('products.show');
    }
}

if (!function_exists('productRoute')) {
    function productRoute(Product $product, Category $category = null, $locale = null, $absolute = true): string
    {
        $locale = $locale ?: app()->getLocale();

        $category = $category ?? $product->category;
        return  route('products.show', [$locale, $category->slug, $product->slug], $absolute);
    }
}

if (!function_exists('str')) {
    function str(string $string): \Illuminate\Support\Stringable
    {
        return Str::of($string);
    }
}

if (!function_exists('title')) {
    function title(string $title): string
    {
        return $title . ' - ' . config('app.name');
    }
}

if (!function_exists('categoryRoute')) {
    function categoryRoute(Category $category, ?Collection $manufacturers = null, ?Collection $choices = null, $min_price = 0, $max_price = 0, $locale = null): ?string
    {
        $name = 'categories.show';
        $params = [
            $locale ?: app()->getLocale(),
            $category->slug
        ];
        
        if ($manufacturers !== null && $manufacturers->isNotEmpty()) {
            $manufacturers = $manufacturers->sortBy('name');
            $name = 'categories.manufacturers';
            $params[] = $manufacturers->pluck('slug')->join('-');
            if ($choices !== null && $choices->isNotEmpty()) {
                $choices = $choices->sortBy(['property.position', 'position']);
                $name = 'categories.manufacturers.filters';
                $params[] = $choices->pluck('slug')->join('-');
            }
        } else if ($choices !== null && $choices->isNotEmpty()) {
            $choices = $choices->sortBy(['property.position', 'position']);
            $name = 'categories.filters';
            $params[] = $choices->pluck('slug')->join('-');
        }

        if (!empty($min_price)) {
            $params['min_price'] = $min_price;
        }

        if (!empty($max_price)) {
            $params['max_price'] = $max_price;
        }

        return route($name, $params);
    }
}

if (!function_exists('user')) {
    function user(): ?User
    {
        return auth()->user();
    }
}

if (!function_exists('dir_size')) {
    function dir_size($disk, $dir, $format = true): string
    {
        $files = Storage::disk($disk)->files($dir);
        $size = 0;
        foreach ($files as $file) {
            $size += Storage::disk($disk)->size($file);
        }

        return $format ? format_bytes($size) : $size;
    }
}

if (!function_exists('format_bytes')) {
    function format_bytes(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 bytes';
        }

        $base = log($bytes) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

        return round(1024 ** ($base - floor($base)), 1) . $suffixes[floor($base)];
    }
}

if (!function_exists('telephone')) {
    function telephone(string $number): string
    {
        return preg_replace('/\s+/', '', $number);
    }
}

if (!function_exists('schema')) {
    function schema(): Schema
    {
        return new Schema();
    }
}