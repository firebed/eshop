<?php


namespace Eshop\Rules;


use Illuminate\Validation\Rules\Unique;

class SeoTitle extends Unique
{
    public function __construct($ignore = NULL, $locale = NULL, $seo_type = null)
    {
        parent::__construct('seo', 'title');

        $this->where('locale', $locale ?? app()->getLocale());

        if ($ignore) {
            $this->where('seo_type', $seo_type ?? $ignore->getMorphClass());
            $this->ignore($ignore->id, 'seo_id');
        }
    }
}