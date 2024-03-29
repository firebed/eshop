<?php

namespace Eshop\Livewire\Dashboard\Category;

use Eshop\Actions\Google\GoogleTranslate;
use Eshop\Models\Product\Category;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Livewire\Component;

class CategoryTranslations extends Component
{
    use SendsNotifications;

    public array  $locales = [];
    public string $default_locale;
    public string $default_locale_name;
    public bool   $show    = false;
    public int    $category_id;

    public array $translations = [];

    public function mount(Category $category): void
    {
        $this->category_id = $category->id;

        $locales = collect(eshop('locales', []));
        $this->default_locale = config('app.locale', '');
        $this->default_locale_name = $locales->get($this->default_locale);
        $locales = eshop('locales', []);
        $this->locales = collect($locales)->except($this->default_locale)->all();

        $seos = $category->seos()->get();

        foreach (array_keys($locales) as $locale) {
            $this->setTranslation($locale, 'category.name', $category->translate('name', $locale, false));
            $this->setTranslation($locale, 'category.description', $category->translate('description', $locale, false));

            $this->setTranslation($locale, 'seo.title', $seos->firstWhere('locale', $locale)->title ?? null);
            $this->setTranslation($locale, 'seo.description', $seos->firstWhere('locale', $locale)->description ?? null);
        }
    }

    public function getDefault(string $key = null, $default = ''): mixed
    {
        if ($key === null) {
            return $this->translations[$this->default_locale];
        }

        return Arr::get($this->translations[$this->default_locale], $key, $default);
    }

    public function getTranslation(string $key, string $locale = null, $default = null)
    {
        if ($locale !== null && array_key_exists($locale, $this->translations)) {
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
        $googleTranslate = new GoogleTranslate();
        return $googleTranslate->handle($q, $target, config('app.locale', ''), $format, []);
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

        if (in_array('category.description', $properties, true)) {
            unset($properties[array_search('category.description', $properties)]);

            $description = $this->getDefault('category.description');
            $result = $this->translateHttp($description, $target);
            $this->setTranslation($target, 'category.description', $result[0]['translatedText']);
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

    public function save(): void
    {
        $category = Category::findOrFail($this->category_id);
        $category->setTranslation('description', $this->getTranslation('category.description', 'el'), 'el');

        foreach (array_keys($this->locales) as $locale) {
            // Save category
            $category->setTranslation('name', $this->getTranslation('category.name', $locale), $locale);
            $category->setTranslation('description', $this->getTranslation('category.description', $locale), $locale);
            $category->save();

            // Save category seo
            $seo = collect($this->getTranslation('seo', $locale))->map(fn($i) => blank($i) ? null : trim($i));
            if ($seo->filter()->isEmpty()) {
                $category->seo($locale)->delete();
            } else {
                $category->seo($locale)->updateOrCreate(['locale' => $locale], $seo->all());
            }
        }

        $this->showSuccessToast(__("Translations were successfully saved"));
        $this->show = false;
    }


    public function render(): Renderable
    {
        return view('eshop::dashboard.category.wire.category-translations');
    }
}