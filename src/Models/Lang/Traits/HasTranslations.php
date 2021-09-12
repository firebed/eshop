<?php


namespace Eshop\Models\Lang\Traits;


use Eshop\Models\Lang\Locale;
use Eshop\Models\Lang\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Str;

/**
 * Trait HasTranslations
 * @package App\Models\Lang\Traits
 *
 * @property Collection  translations
 * @property Translation translation
 * @property array       translatable
 *
 * @method MorphMany morphMany($related, $name)
 * @method MorphOne  morphOne($related, $name)
 */
trait HasTranslations
{
    protected string $translationLocale;
    protected array  $translationAttributes = [];

    protected static function bootHasTranslations(): void
    {
        static::saved(fn($model) => $model->saveTranslations());

        static::deleting(function ($model) {
            $isSoftDelete = in_array(SoftDeletes::class, class_uses($model), false);
            if (!$isSoftDelete || $model->isForceDeleting()) {
                $model->deleteTranslations();
            }
        });
    }

    public function translations(?string $cluster = null, ?string $locale = null): MorphMany
    {
        return $this
            ->morphMany(Translation::class, 'translatable')
            ->when(!is_null($cluster), fn($q) => $q->where('cluster', $cluster))
            ->when(!is_null($locale), fn($q) => $q->where('locale', $locale));
    }

    public function scopeJoinTranslation(Builder $builder, string $cluster = 'name'): void
    {
        $builder->selectRaw("translations.translation as $cluster");
        $builder->join('translations', function (JoinClause $q) use ($cluster) {
            $q->on('translations.translatable_id', '=', $this->getQualifiedKeyName());
            $q->where('translations.translatable_type', $this->getMorphClass());
            $q->where('translations.locale', $this->getLocale());
            $q->where('translations.cluster', $cluster);
        });
    }

    public function translation(?string $cluster = null, ?string $locale = null)
    {
        return $this
            ->morphOne(Translation::class, 'translatable')
            ->when(!is_null($cluster), fn($q) => $q->where('cluster', $cluster))
            ->when(!is_null($locale), fn($q) => $q->where('locale', $locale));
    }

    public function translate(string $cluster, ?string $locale = null, bool $useFallbackLocale = true): ?string
    {
        if ($val = parent::getAttribute($cluster)) {
            return $val;
        }

        $locale = $locale ?? $this->getLocale();

        if (isset($this->translationAttributes[$cluster][$locale])) {
            if ($this->hasGetMutator($cluster)) {
                return $this->mutateAttribute($cluster, $this->translationAttributes[$cluster][$locale]);
            }
            return $this->translationAttributes[$cluster][$locale];
        }

        if ($this->relationLoaded('translation')) {
            $translation = $this->translation;
        } else {
            $translations = $this->translations;
            $translation = $translations->where('cluster', $cluster)->where('locale', $locale)->first();
        }

        if ($useFallbackLocale && is_null($translation) && $locale !== $this->getFallbackLocale()) {
            return $this->fallbackTranslate($cluster);
        }

        $text = $translation->translation ?? null;
        if ($this->hasGetMutator($cluster)) {
            return $this->mutateAttribute($cluster, $text);
        }

        return $text;
    }

    public function setTranslation(string $cluster, ?string $translation, ?string $locale = null): void
    {
        $locale = $locale ?? $this->getLocale();

        if (method_exists($this, 'set' . Str::studly($cluster) . 'Attribute')) {
            $this->{'set' . Str::studly($cluster) . 'Attribute'}($translation, $locale);
        }

        $this->translationAttributes[$cluster][$locale] = $translation;
    }

    public function setTranslations(string $cluster, array $translations): void
    {
        $locales = Locale::all();
        foreach ($locales as $locale) {
            $this->setTranslation($cluster, $translations[$locale->name] ?? null, $locale->name);
        }
    }

    public function saveTranslations(): void
    {
        foreach ($this->translationAttributes as $cluster => $translations) {
            foreach ($translations as $locale => $translation) {
                if (empty($translation)) {
                    $this->translation($cluster, $locale)->delete();
                } else {
                    $this->translations()->updateOrCreate(
                        ['cluster' => $cluster, 'locale' => $locale],
                        ['translation' => $this->translationAttributes[$cluster][$locale] ?? $translation]
                    );
                }
            }
        }
        $this->translationAttributes = [];

        if ($this->relationLoaded('translations')) {
            $this->unsetRelation('translations');
        }
    }

    public function fallbackTranslate(string $cluster): ?string
    {
        return $this->translate($cluster, $this->getFallbackLocale(), false);
    }

    public function getTranslatableAttributes(): array
    {
        return is_array($this->translatable) ? $this->translatable : [];
    }

    public function isTranslatableAttribute(string $key): bool
    {
        return in_array($key, $this->getTranslatableAttributes(), false);
    }

    public function getLocale(): string
    {
        return config('app.locale');
    }

    public function getFallbackLocale()
    {
        return config('translatable.fallback_locale');
    }

    public function deleteTranslations(?string $cluster = null, ?string $locale = null): void
    {
        $this->translations($cluster, $locale)->delete();
    }

    public function getAttribute($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttribute($key);
        }

        return $this->translate($key);
    }

    public function setAttribute($key, $value): void
    {
        if (!$this->isTranslatableAttribute($key)) {
            parent::setAttribute($key, $value);
        } else {
            $this->setTranslation($key, $value);
        }
    }
}
