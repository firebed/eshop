<?php


namespace Eshop\Models\Seo\Traits;


use Eshop\Models\Seo\Seo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::deleting(function ($model) {
            $isSoftDelete = in_array(SoftDeletes::class, class_uses($model), FALSE);
            if (!$isSoftDelete || $model->isForceDeleting()) {
                $model->seos()->delete();
            }
        });
    }

    public function seo($locale = NULL): MorphOne
    {
        $locale = $locale ?? config('app.locale');

        return $this->morphOne(Seo::class, 'seo')->where('locale', $locale);
    }

    public function seos(): MorphMany
    {
        return $this->morphMany(Seo::class, 'seo');
    }
}