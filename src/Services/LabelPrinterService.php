<?php

namespace Eshop\Services;

use Illuminate\Support\Facades\Cache;

class LabelPrinterService
{
    public function update(string $width, $height, $margin, $fontSize): void
    {
        $attributes = $this->attributes();
        $attributes['width'] = $width;
        $attributes['height'] = $height;
        $attributes['margin'] = $margin;
        $attributes['fontSize'] = $fontSize;
        $this->cache($attributes);
    }

    public function width(): int
    {
        return $this->get('width', eshop('label.width', 35));
    }

    public function height(): int
    {
        return $this->get('height', eshop('label.height', 24));
    }

    public function margin(): int
    {
        return $this->get('margin', eshop('label.margin', 1));
    }

    public function fontSize(): int
    {
        return $this->get('font-size', eshop('label.font-size', 9));
    }
    
    public function get(string $key, $default = null): string
    {
        return $this->attributes()[$key] ?? $default;
    }

    public function put(string $key, string $value): string
    {
        $attributes = $this->attributes();
        $attributes[$key] = $value;
        $this->cache($attributes);
        return $value;
    }

    public function attributes(): array
    {
        return Cache::get($this->getKey(), []);
    }

    private function cache(array $attributes): void
    {
        Cache::forget($this->getKey());
        Cache::rememberForever($this->getKey(), fn() => $attributes);
    }

    private function getKey(): string
    {
        return 'product-label-' . auth()->id();
    }
}