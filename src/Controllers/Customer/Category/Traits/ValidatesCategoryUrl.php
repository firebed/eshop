<?php


namespace Eshop\Controllers\Customer\Category\Traits;


use Illuminate\Support\Collection;

trait ValidatesCategoryUrl
{
    /**
     * For SEO purposes we must make sure that the urls are consistent.
     * Reordering of segments or parameters are not allowed. Otherwise,
     * Google will treat those urls as duplicates.
     *
     * @param Collection  $filters
     * @param string|null $user_manufacturers
     * @param string|null $user_choices
     * @return bool
     */
    protected function validateUrl(Collection $filters, ?string $user_manufacturers, ?string $user_choices): bool
    {
        return $filters['m']->pluck('slug')->join('+') === ($user_manufacturers ?? "")
            && $filters['c']->pluck('slug')->join('+') === ($user_choices ?? "");
    }
}
