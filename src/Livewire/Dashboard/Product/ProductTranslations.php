<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Models\Product\Product;
use Eshop\Models\Product\ProductVariantOption;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ProductTranslations extends Component
{
    use SendsNotifications;

    public array  $locales;
    public string $default_locale;
    public string $default_locale_name;
    public bool   $has_variants = false;

    public int   $product_id;
    public array $translations = [];

    public function mount(Product $product): void
    {
        $this->product_id = $product->id;
        $this->has_variants = $product->has_variants;

        $locales = collect(eshop('locales', []));
        $this->default_locale = config('app.locale', '');
        $this->default_locale_name = $locales->get($this->default_locale);
        $locales = eshop('locales', []);
        $this->locales = collect($locales)->except($this->default_locale)->all();

        $seos = $product->seos()->get();
        $variants = $product->variants()->with('options.translations', 'seos')->get();
        (new EloquentCollection($variants->pluck('options')->flatten()->pluck('pivot')))->load('translations');

        $variantTypes = $variants->pluck('options')->flatten()->unique('id');
        foreach (array_keys($locales) as $locale) {
            $this->setTranslation($locale, 'product.name', $product->translate('name', $locale, false));
            $this->setTranslation($locale, 'product.description', $product->translate('description', $locale, false));

            $this->setTranslation($locale, 'seo.title', $seos->firstWhere('locale', $locale)->title ?? null);
            $this->setTranslation($locale, 'seo.description', $seos->firstWhere('locale', $locale)->description ?? null);

            foreach ($variantTypes as $variantType) {
                $this->setTranslation($locale, "variant_types.$variantType->id", $variantType->translate('name', $locale, false));
            }

            $options = $variants->pluck('options')->collapse()->pluck('pivot')->sortBy('variant_type_id');
            foreach ($options as $option) {
                $this->setTranslation($locale, "options.$option->id", $option->translate('name', $locale, false));
            }

            foreach ($variants as $variant) {
                $this->setTranslation($locale, "variants_seo.$variant->id", $variant->seos->firstWhere('locale', $locale)->title ?? null);
            }
        }
    }

    public function translate($properties, string $target): void
    {
        $count = $this->doTranslate($properties, $target);

        $this->showSuccessToast('Μετάφραση', "Μεταφράστηκαν $count χαρακτήρες.", true, 5000);
    }

    public function doTranslate($properties, string $target): int
    {
        $count = 0;
        $properties = is_array($properties) ? $properties : [$properties];

        if (in_array('product.description', $properties, true)) {
            unset($properties[array_search('product.description', $properties)]);

            $description = $this->getDefault('product.description');
            $result = $this->translateHttp($description, $target);
            $this->setTranslation($target, 'product.description', $result[0]['translatedText']);
            $count += mb_strlen($result[0]['translatedText']);
        }

        foreach ($properties as $property) {
            $values = Arr::get($this->getDefault(), $property);
            $keys = is_array($values) ? array_map(static fn($key) => "$property.$key", array_keys($values)) : $property;

            $q = is_array($values) ? Arr::flatten($values) : $values;

            $results = $this->translateHttp($q, $target);

            $iMax = count($results);
            for ($i = 0; $i < $iMax; $i++) {
                $this->setTranslation($target, is_array($keys) ? $keys[$i] : $keys, $results[$i]['translatedText']);
            }

            if (is_array($q)) {
                $count += array_reduce($q, static fn($c, $i) => $c + mb_strlen($i), 0);
            } else {
                $count += mb_strlen($q);
            }
        }

        return $count;
    }

    public function translateAll(string $target): void
    {
        $count = 0;
        foreach (array_keys($this->getDefault()) as $key) {
            $count += $this->doTranslate($key, $target);
        }

        $this->showSuccessToast('Μετάφραση', "Μεταφράστηκαν $count χαρακτήρες.", true, 5000);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product-translation.wire.index');
    }

    public function getDefault(string $key = null): mixed
    {
        if ($key === null) {
            return $this->translations[$this->default_locale];
        }

        return Arr::get($this->translations[$this->default_locale], $key, '');
    }

    public function getTranslation(string $key, string $locale = null, $default = null)
    {
        if ($locale !== null) {
            return Arr::get($this->translations[$locale], $key, $default);
        }

        return Arr::get($this->translations, $key, '');
    }

    public function setTranslation(string $locale, string $key, ?string $value): void
    {
        Arr::set($this->translations[$locale], $key, $value);
    }

    public function translateHttp($q, $target, $format = 'text'): ?array
    {
        $key = api_key('GOOGLE_TRANSLATE_API_KEY');

        if (blank($key)) {
            $this->showErrorToast("Σφάλμα", "Δεν έχει οριστεί κλειδί εισόδου.");
            return null;
        }

        $source = config('app.locale', '');

        $http = Http::post("https://translation.googleapis.com/language/translate/v2?key=$key", [
            'q'      => $q,
            'target' => $target,
            'format' => $format,
            'source' => $source
        ]);

        return $http->json('data.translations', []);
    }

    public function save(): void
    {
        $product = Product::findOrFail($this->product_id);

        foreach (array_keys($this->locales) as $locale) {
            // Save product
            $product->setTranslation('name', $this->getTranslation('product.name', $locale), $locale);
            $product->setTranslation('description', $this->getTranslation('product.description', $locale), $locale);
            $product->save();

            // Save product seo
            $seo = collect($this->getTranslation('seo', $locale))->map(fn($i) => blank($i) ? null : trim($i));
            if ($seo->filter()->isEmpty()) {
                $product->seo($locale)->delete();
            } else {
                $product->seo($locale)->updateOrCreate(['locale' => $locale], $seo->all());
            }

            // Save variant types
            $variant_types = collect($this->getTranslation('variant_types', $locale, []))->map(fn($i) => blank($i) ? null : trim($i));
            $types = $product->variantTypes()->get();
            foreach ($variant_types as $id => $name) {
                $variant_type = $types->find($id);
                if ($variant_type) {
                    $variant_type->setTranslation('name', $name, $locale);
                    $variant_type->save();
                }
            }

            // Save variant seo
            $variants_seo = collect($this->getTranslation('variants_seo', $locale, []))->map(fn($i) => blank($i) ? null : trim($i));
            $variants = $product->variants()->with('seos')->get();
            foreach ($variants_seo as $variant_id => $seo) {
                $variant = $this->getVariant($variants, $variant_id);
                if ($variant === null) {
                    continue;
                }

                if (is_null($seo)) {
                    $variant->seo($locale)->delete();
                } else {
                    $variant->seos()->updateOrCreate(['locale' => $locale], ['title' => $seo]);
                }
            }

            // Save variant options (colours, sizes, etc)
            $options = collect($this->getTranslation('options', $locale, []))->map(fn($i) => blank($i) ? null : trim($i));
            $collection = ProductVariantOption::whereKey(array_keys($this->getDefault('options')))->get();
            foreach ($options as $id => $name) {
                $option = $collection->find($id);
                if ($option) {
                    $option->setTranslation('name', $name, $locale);
                    $option->save();
                }
            }
        }

        $this->showSuccessToast(__("Translations were successfully saved"));
    }

    private function getVariant(Collection $variants, $id): ?Product
    {
        return $variants->find($id);
    }
}
