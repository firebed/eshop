<?php


namespace Eshop\Rules;


use Illuminate\Validation\Rules\Unique;

class UniqueSlug extends Unique
{
    public function __construct($table, $column = 'NULL', $ignore = NULL)
    {
        parent::__construct($table, $column);

        if ($ignore) {
            $this->ignore($ignore);
        }
    }

}