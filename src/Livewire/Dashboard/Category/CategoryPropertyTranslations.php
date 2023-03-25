<?php

namespace Eshop\Livewire\Dashboard\Category;

use Eshop\Actions\Google\GoogleTranslate;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryProperty;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Livewire\Component;

class CategoryPropertyTranslations extends Component
{
    use SendsNotifications;

    public array  $locales = [];
    public string $default_locale;
    public string $default_locale_name;
    public bool   $show    = false;
    public int    $property_id;

    public array $translations = [];

    public function mount(CategoryProperty $property): void
    {
        $this->property_id = $property->id;

        $locales = collect(eshop('locales', []));
        $this->default_locale = config('app.locale', '');
        $this->default_locale_name = $locales->get($this->default_locale);
        $locales = eshop('locales', []);
        $this->locales = collect($locales)->except($this->default_locale)->all();

        $choices = $property->choices()->with('translations')->get();
        
        foreach (array_keys($locales) as $locale) {
            $this->setTranslation($locale, 'property.name', $property->translate('name', $locale, false));
            
            foreach ($choices as $choice) {
                $this->setTranslation($locale, "choices.$choice->id", $choice->translate('name', $locale, false));
            }
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
        $properties = Arr::wrap($properties);
        
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
        $property = CategoryProperty::findOrFail($this->property_id);

        foreach (array_keys($this->locales) as $locale) {
            // Save property
            $property->setTranslation('name', $this->getTranslation('property.name', $locale), $locale);
            $property->save();

            // Save choices seo
            $translated_choices = collect($this->getTranslation('choices', $locale))->map(fn($i) => blank($i) ? null : trim($i));
            $choices = $property->choices()->get();
            foreach ($translated_choices as $id => $name) {
                $choice = $choices->find($id);
                $choice->setTranslation('name', $name, $locale);
                $choice->save();
            }
        }

        $this->showSuccessToast(__("Translations were successfully saved"));
        $this->show = false;
    }


    public function render(): Renderable
    {
        return view('eshop::dashboard.category-property.wire.category-property-translations');
    }
}