<?php


namespace Eshop\Rules;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class SeoTitle extends Unique
{
    public function __construct($ignore = null, $locale = null, $seo_type = null)
    {
        parent::__construct('seo', 'title');

        $this->where('locale', $locale ?? app()->getLocale());

        if ($ignore instanceof Model) {
            $this->where('seo_type', $seo_type ?? $ignore->getMorphClass());
            $this->ignore($ignore->id, 'seo_id');
        } elseif (is_string($ignore)) {
            $this->where('seo_type', $ignore);
        }
    }
}