<?php


namespace Eshop\Rules;


use Illuminate\Validation\Rules\Unique;

class UniqueTranslation extends Unique
{
    public function __construct($ignore = NULL, $locale = NULL, $translatable_type = NULL)
    {
        parent::__construct('translations', 'translation');

        $this->where('locale', $locale ?? app()->getLocale());

        if ($ignore) {
            $this->where('translatable_type', $translatable_type ?? $ignore->getMorphClass());
            $this->ignore($ignore->id, 'translatable_id');
        }
    }

}